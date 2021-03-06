<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Api\Procedure;

use Jitamin\Api\Authorization\ProjectAuthorization;
use Jitamin\Core\ObjectStorage\ObjectStorageException;

/**
 * Project File API controller.
 */
class ProjectFileProcedure extends BaseProcedure
{
    public function getProjectFile($project_id, $file_id)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'getProjectFile', $project_id);

        return $this->projectFileModel->getById($file_id);
    }

    public function getAllProjectFiles($project_id)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'getAllProjectFiles', $project_id);

        return $this->projectFileModel->getAll($project_id);
    }

    public function downloadProjectFile($project_id, $file_id)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'downloadProjectFile', $project_id);

        try {
            $file = $this->projectFileModel->getById($file_id);

            if (!empty($file)) {
                return base64_encode($this->objectStorage->get($file['path']));
            }
        } catch (ObjectStorageException $e) {
            $this->logger->error($e->getMessage());
        }

        return '';
    }

    public function createProjectFile($project_id, $filename, $blob)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'createProjectFile', $project_id);

        try {
            return $this->projectFileModel->uploadContent($project_id, $filename, $blob);
        } catch (ObjectStorageException $e) {
            $this->logger->error(__METHOD__.': '.$e->getMessage());

            return false;
        }
    }

    public function removeProjectFile($project_id, $file_id)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'removeProjectFile', $project_id);

        return $this->projectFileModel->remove($file_id);
    }

    public function removeAllProjectFiles($project_id)
    {
        ProjectAuthorization::getInstance($this->container)->check($this->getClassName(), 'removeAllProjectFiles', $project_id);

        return $this->projectFileModel->removeAll($project_id);
    }
}
