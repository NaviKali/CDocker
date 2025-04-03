<?php

namespace tank\Upload;

use tank\Error\uploadfiletypeError;
use tank\Tool\Tool as Tool;
use config\Upload as Configupload;

use function tank\dump;
use function tank\getConfigUrl;
use function tank\getAPPJSON;
use function tank\getRoot;

/**
 * 文件上传
 * @author LL
 * @access public
 */
class Upload
{
        /**
         * 文件上传配置
         * @var array
         */
        public array $uploadConfig;
        /**
         * 文件磁盘
         * @var string
         */
        public string $disk;
        /**
         * 文件路径
         * @var string
         */
        public string $fileUrl;
        /**
         * 文件上传支持后缀类型
         * @var array
         */
        public array $fileSuffixSupportType;
        /**
         * 上传文件全称
         */
        public string $fileAddress;

        /**
         * 构造 [如果需要文件上传那么则填写对应构造参数]
         * @access public
         * @param string $fileName 文件名字 选填 默认为 ''
         * @param string $fileType 文件类型 选填 默认为 ''
         */
        public function __construct(public string $fileName = '', public string $fileType = '')
        {
                $this->fileAddress = $this->fileName . '.' . $this->fileType;
                $this->uploadConfig = require_once getConfigUrl() . "/Upload.php";
                $this->disk = getRoot() . $this->uploadConfig["disk"];
                $this->fileSuffixSupportType = $this->uploadConfig["fileSuffixSupportType"];
                $this->VerFileType($this->fileType);
                $this->fileUrl = $this->disk . $this->fileAddress;
        }
        /**
         * ?文件类型验证
         * @access protected
         * @param string $fileType 文件类型 必填
         * @throws uploadfiletypeError
         */
        protected function VerFileType(string $fileType)
        {
                if (!in_array($fileType, $this->fileSuffixSupportType))
                        throw new uploadfiletypeError();
        }
        /**
         * 获取磁盘名称
         * @access public
         * @return string
         */
        public function getDiskName(): string
        {
                return $this->disk;
        }
        /**
         * 获取文件上传支持后缀类型列表
         * @access public
         * @return array
         */
        public function getFileSuffixSupportTypeList(): array
        {
                return $this->fileSuffixSupportType;
        }
        /**
         * 重定义磁盘
         * @access public
         * @param string $diskName 磁盘名称 必填
         * @return Upload
         */
        public function setDisk(string $diskName): Upload
        {
                $this->disk = getRoot() . "/$diskName";
                return $this;
        }
        /**
         * 重定义文件上传支持后缀类型
         * @access public
         * @param array $type 支持类型 必填
         * @return Upload
         */
        public function setFileSuffixSupportTypeList(array $type): Upload
        {
                $this->fileSuffixSupportType = $type;
                $this->VerFileType($this->fileType);
                return $this;
        }
        /**
         * Base64文件上传
         * @access public
         * @param string $data 写入流 必填
         * @return bool
         */
        public function Base64Upload(string $data): bool
        {
                $data = base64_decode($data);
                $this->FileWrite($this->fileAddress, $data);
                return true;
        }
        /**
         * 路由编码文件上传
         * @access public
         * @param string $data 写入流 必填
         * @return true
         */
        public function UrlUpload(string $data): bool
        {
                $data = urldecode($data);
                $this->FileWrite($this->fileAddress, $data);
                return true;
        }
        /**
         * 文件上传
         * @access public
         * @param string $data 写入流 必填
         * @return true
         */
        public function Upload(string $data): bool
        {
                $this->FileWrite($this->fileAddress, $data);
                return true;
        }
        /**
         * 文件写入流
         * @access public
         * @param string $file 文件名字 必填
         * @param string $data 写入流 必填
         * @return Upload
         */
        public function FileWrite(string $file, string $data): Upload
        {
                Tool::WriteFile($this->getDiskName() . "/$file", $data);
                return $this;
        }
        /**
         * 获取文件上传全路径
         * @access public
         * @return string
         */
        public function getUploadFileUrl(): string
        {
                return $this->fileUrl;
        }
        /**
         * 获取磁盘所有文件
         * @access public
         * @return array
         */
        public function getDiskFiles(): array
        {
                $arr = [];
                foreach (glob($this->disk . "/*") as $k => $v) {
                        array_push($arr, str_replace($this->disk . '/', '', $v));
                }
                return $arr;
        }
}
