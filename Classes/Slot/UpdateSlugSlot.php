<?php
namespace SpoonerWeb\SlugExtbase\Slot;

/*
 * This file is part of a TYPO3 extension.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use SpoonerWeb\SlugExtbase\SlugEntityInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\Model\RecordStateFactory;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;

class UpdateSlugSlot
{

    public function updateSlugForObject(DomainObjectInterface $object)
    {
        if ($object instanceof SlugEntityInterface) {
            [$tableName, $slugFieldName] = $this->getTableAndSlugFieldName($object);
            if ($tableName && $slugFieldName) {
                $fieldsToWatch = $GLOBALS['TCA'][$tableName]['columns'][$slugFieldName]['config']['generatorOptions']['fields'];
                foreach ($fieldsToWatch as $fieldToWatch) {
                    $fieldNameInObject = GeneralUtility::underscoredToLowerCamelCase($fieldToWatch);
                    if ($object->_isDirty($fieldNameInObject)) {
                        $this->generateAndSaveSlug($object->getUid(), $tableName, $slugFieldName);
                    }
                }
            }
        }
    }

    protected function getTableAndSlugFieldName(DomainObjectInterface $object)
    {
        $tableName = GeneralUtility::makeInstance(ObjectManager::class)
            ->get(DataMapper::class)
            ->convertClassNameToTableName(get_class($object));
        foreach ($GLOBALS['TCA'][$tableName]['columns'] as $field => $config) {
            if ($config['config']['type'] === 'slug') {
                $slugFieldName = $field;
                break;
            }
        }

        return [$tableName, $slugFieldName];
    }

    protected function generateAndSaveSlug(int $objectUid, string $tableName, string $slugFieldName)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($tableName);

        $record = $queryBuilder->select('*')
            ->from($tableName)
            ->where($queryBuilder->expr()->eq('uid', $objectUid))
            ->execute()
            ->fetch();

        $helper = GeneralUtility::makeInstance(SlugHelper::class, $tableName, $slugFieldName,
            $GLOBALS['TCA'][$tableName]['columns'][$slugFieldName]['config']);

        $value = $helper->generate($record, $record['pid']);
        $state = RecordStateFactory::forName($tableName)->fromArray($record, $record['pid'], $record['uid']);

        if (GeneralUtility::inList($GLOBALS['TCA'][$tableName]['columns'][$slugFieldName]['config']['eval'], 'uniqueInPid')) {
            $value = $helper->buildSlugForUniqueInPid($value, $state);
        } else {
            $value = $helper->buildSlugForUniqueInSite($value, $state);
        }

        $queryBuilder->update($tableName)
            ->where($queryBuilder->expr()->eq('uid', $objectUid))
            ->set($slugFieldName, $value)
            ->execute();
    }
}
