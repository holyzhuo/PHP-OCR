<?php

class Ocr
{
    const SAVE_PATH = './runtime-img';

    public $imgPath;
    public $resource;

    private $lang = 'eng'; // 默认识别英文

    /**
     * Ocr constructor.
     * @param $imgPath string 图片路径，支持本地图片和远程图片
     */
    public function __construct(string $imgPath)
    {
        $this->imgPath  = $imgPath;
        $this->resource = $this->getImageSource();
    }

    /**
     *  获取识别结果
     * @return string
     */
    public function getRecognition(): string
    {
        return $this->handleImg();
    }

    /**
     * 设置识别的语言
     * @param string $lang
     */
    public function setLang(string $lang)
    {
        $this->lang = $lang;
    }

    /**
     * 转换成小写
     * @param string $str
     * @return string
     */
    public function lower(string $str): string
    {
        return mb_strtolower($str, 'UTF-8');
    }

    /**
     * 调用TesseractOCR处理图片
     * @return string
     */
    public function handleImg(): string
    {
        ob_start();
        $this->readImage();
        $size = ob_get_length();
        $data = ob_get_clean();

        $ocr = new \thiagoalessio\TesseractOCR\TesseractOCR();
        $ocr->imageData($data, $size);
        $result = $this->lower($ocr->lang($this->lang)->run());
        return $result;
    }

    /**
     * 读取图片资源
     * @return mixed
     */
    protected function getImageSource()
    {
        $type = substr(strrchr($this->imgPath, '.'), 1);
        //不同的图片类型选择不同的图片生成和保存函数
        switch ($type) {
            case 'jpeg':
                $img_create_func = 'imagecreatefromjpeg';
                break;
            case 'png':
                $img_create_func = 'imagecreatefrompng';
                break;
            case 'bmp':
                $img_create_func = 'imagecreatefrombmp';
                break;
            case 'gif':
                $img_create_func = 'imagecreatefromgif';
                break;
            case 'vnd.wap.wbmp':
                $img_create_func = 'imagecreatefromwbmp';
                break;
            case 'xbm':
                $img_create_func = 'imagecreatefromxbm';
                break;
            default:
                $img_create_func = 'imagecreatefromjpeg';
        }

        $src_im = $img_create_func($this->imgPath); //由url创建新图片
        return $src_im;
    }

    /**
     * 读取图片到内存
     */
    public function readImage()
    {
        $type = substr(strrchr($this->imgPath, '.'), 1);
        //不同的图片类型选择不同的图片生成和保存函数
        switch ($type) {
            case 'jpeg':
                $img_save_func = 'imagejpeg';
                $args          = [
                    $this->resource,
                    null,
                    100,
                ];
                break;
            case 'png':
                $img_save_func = 'imagepng';
                $args          = [
                    $this->resource,
                    null,
                    9,
                ];
                break;
            case 'bmp':
                $img_save_func = 'imagebmp';
                $args          = [
                    $this->resource,
                    null,
                ];
                break;
            case 'gif':
                $img_save_func = 'imagegif';
                $args          = [
                    $this->resource,
                    null,
                ];
                break;
            case 'vnd.wap.wbmp':
                $img_save_func = 'imagewbmp';
                $args          = [
                    $this->resource,
                    null,
                ];
                break;
            case 'xbm':
                $img_save_func = 'imagexbm';
                $args          = [
                    $this->resource,
                    null,
                ];
                break;
            default:
                $img_save_func = 'imagejpeg';
                $args          = [
                    $this->resource,
                    null,
                    100,
                ];
        }

        call_user_func_array($img_save_func, $args);
    }

    /**
     * @param $angle int 图片旋转角度
     */
    public function rotate($angle)
    {
        $this->resource = imagerotate($this->resource, $angle, 0);
    }

    /**
     * 保存图片到指定路径
     * @return string
     */
    public function save()
    {
        $path = self::SAVE_PATH . '/' . uniqid() . '.jpeg';
        imagejpeg($this->resource, $path);

        return $path;
    }
}