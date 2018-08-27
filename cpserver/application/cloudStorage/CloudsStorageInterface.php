<?php

namespace app\cloudStorage;

interface CloudsStorageInterface
{
    function uploadFile(string $uploadFilePath, string $cloudSavePath);
    function deleteFile(string $cloudSavePath);
    function getFile(string $cloudSavePath);
}