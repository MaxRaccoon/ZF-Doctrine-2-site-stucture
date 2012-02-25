<?php

/**
 * Zend Image
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @category  Zend
 * @package   Zend_Image
 * @author    Stanislav Seletskiy <s.seletskiy@gmail.com>
 * @author    Leonid Shagabutdinov <leonid@shagabutdinov.com>
 * @copyright Copyright (c) 2010
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version   $Id: Transform.php 51 2010-03-13 06:25:04Z leonid@shagabutdinov.com $
 */


/**
 * @see Zend_Image
 */
require_once 'Zend/Image.php';

/**
 * @see Zend_Image_Transform_Exception
 */
require_once 'Zend/Image/Transform/Exception.php';


/**
 * @todo Negative values for crop.
 * @todo Asserts, that position methods are not called together.
 * @todo phpDoc descriptions.
 * @category  Zend
 * @package   Zend_Image
 * @copyright Copyright (c) 2010
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Zend_Image_Transform extends Zend_Image
{
    /**
     * Resize image to specified coordinats
     *
     * @throws Zend_Image_Driver_Exception
     * @param int $width
     * @param int $height
     * @return Zend_Image
     */
    public function resize( $width, $height )
    {
        $this->_driver->resize( $width, $height );
        return $this;
    }


    /**
     * Fits image to target width.
     *
     * @throws Zend_Image_Driver_Exception
     * @throws Zend_Image_Transform_Exception
     * @param   int $targetWidth Width to fit to.
     * @return  Zend_Image
     */
    public function fitToWidth( $targetWidth )
    {
        list( $sourceWidth, $sourceHeight ) = $this->_driver->getSize();

        if( $sourceWidth <= 0 ) {
            throw new Zend_Image_Transform_Exception( 'Source width can\'t be 0' );
        }

        if( $sourceHeight <= 0 ) {
            throw new Zend_Image_Transform_Exception( 'Source height can\'t be 0' );
        }

        $targetHeight = round( $sourceHeight / $sourceWidth * $targetWidth );

        $this->_driver->resize( $targetWidth, $targetHeight );
        return $this;
    }


    /**
     * Fits image to target height.
     *
     * @throws Zend_Image_Driver_Exception
     * @throws Zend_Image_Transform_Exception
     * @param   int $targetHeight Height to fit to.
     * @return  Zend_Image
     */
    public function fitToHeight( $targetHeight )
    {
        list( $sourceWidth, $sourceHeight ) = $this->_driver->getSize();

        if( $sourceWidth <= 0 ) {
            throw new Zend_Image_Transform_Exception( 'Source width can\'t be 0' );
        }

        if( $sourceHeight <= 0 ) {
            throw new Zend_Image_Transform_Exception( 'Source height can\'t be 0' );
        }

        $targetWidth = round( $sourceWidth / $sourceHeight * $targetHeight );

        $this->_driver->resize( $targetWidth, $targetHeight );
        return $this;
    }


    /**
     * Fits image into specified frame.
     *
     * @throws Zend_Image_Driver_Exception
     * @throws Zend_Image_Transform_Exception
     * @param   int $targetWidth Frame width.
     * @param   int $targetHeight Frame height.
     * @return  Zend_Image
     */
    public function fitIn( $targetWidth, $targetHeight )
    {
        $targetRatio = $targetWidth / $targetHeight;
        list( $sourceWidth, $sourceHeight ) = $this->_driver->getSize();
        $sourceRatio = $sourceWidth / $sourceHeight;

        if ( $targetRatio < $sourceRatio ) {
            $this->fitToWidth( $targetWidth );
        } else {
            $this->fitToHeight( $targetHeight );
        }

        return $this;
    }


    /**
     * Fits image out of frame.
     *
     * @throws Zend_Image_Driver_Exception
     * @throws Zend_Image_Transform_Exception
     * @param   $targetWidth int Frame width.
     * @param   $targetHeight int Frame height.
     * @return  Zend_Image
     */
    public function fitOut( $targetWidth, $targetHeight )
    {
        $targetRatio = $targetWidth / $targetHeight;
        list( $sourceWidth, $sourceHeight ) = $this->_driver->getSize();
        $sourceRatio = $sourceWidth / $sourceHeight;

        if ( $targetRatio > $sourceRatio ) {
            $this->fitToWidth( $targetWidth );
        } else {
            $this->fitToHeight( $targetHeight );
        }

        return $this;
    }


    /**
     * Offset from left border of image.
     *
     * @param   $leftOffset int Left offset.
     * @return  Zend_Image
     */
    public function left( $leftOffset = 0 )
    {
        $this->_leftOffset = intval( $leftOffset );
        return $this;
    }


    /**
     * Offset from right border of image.
     *
     * @param   $rightOffset int Right offset.
     * @return  Zend_Image
     */
    public function right( $rightOffset = 0 )
    {
        $this->_rightOffset = intval( $rightOffset );
        return $this;
    }


    /**
     * Offset from top border of image.
     *
     * @param   $topOffset int Top offset.
     * @return  Zend_Image
     */
    public function top( $topOffset = 0 )
    {
        $this->_topOffset = intval( $topOffset );
        return $this;
    }


    /**
     * Offset from bottom border of image.
     *
     * @param   $bottomOffset int Bottom offset.
     * @return  Zend_Image
     */
    public function bottom( $bottomOffset = 0 )
    {
        $this->_bottomOffset = intval( $bottomOffset );
        return $this;
    }


    /**
     * Sets crop position from horizontal center.
     *
     * @return  Zend_Image
     */
    public function center()
    {
        $this->_center = true;
        return $this;
    }


    /**
     * Sets crop position from vertical middle.
     *
     * @return  Zend_Image
     */
    public function middle()
    {
        $this->_middle = true;
        return $this;
    }


    /**
     * Crops image from specified by left(), top(), bottom() or right() point.
     *
     * @throws Zend_Image_Driver_Exception
     * @throws Zend_Image_Transform_Exception
     * @param   $width int Width of cropped image.
     * @param   $height int Height of cropped image.
     * @return  Zend_Image
     */
    public function crop( $width, $height )
    {
        $this->_checkOffsets();

        $leftOffset = $this->_calcLeftOffset();
        $topOffset = $this->_calcTopOffset();

        if ( $this->_center ) {
            $leftOffset = round( ( $this->getWidth() - $width ) / 2 );
        }

        if ( $this->_middle ) {
            $topOffset = round( ( $this->getHeight() - $height ) / 2 );
        }

        if ( $height < 0 ) {
            $topOffset += $height;
            $height = -$height;
        }

        if ( $width < 0 ) {
            $leftOffset += $width;
            $width = -$width;
        }

        //$this->_checkOutOfBounds( $leftOffset, $topOffset, $width, $height );

        $this->_driver->crop( $leftOffset, $topOffset, $width, $height );

        return $this;
    }


    /**
     * Check opposite offsets can not be setted.
     */
    private function _checkOffsets()
    {
        if( $this->_leftOffset > 0 && $this->_rightOffset > 0 ) {
            throw new Zend_Image_Transform_Exception(
                'Both of right and left was setted. Right and left offset ' .
                    'can\'t be setted at same time.'
            );
        }

        if( $this->_center && $this->_leftOffset > 0 ) {
            throw new Zend_Image_Transform_Exception(
                'Both of center and left was setted. Center and left can\'t ' .
                    'be setted at same time'
            );
        }

        if( $this->_center && $this->_rightOffset > 0 ) {
            throw new Zend_Image_Transform_Exception(
                'Both of center and right was setted. Center and right can\'t ' .
                    'be setted at same time'
            );
        }

        if( $this->_topOffset && $this->_bottomOffset ) {
            throw new Zend_Image_Transform_Exception(
                'Both of top and bottom was setted. Right and left offset ' .
                    'can\'t be setted at same time.'
            );
        }

        if( $this->_middle && $this->_topOffset > 0 ) {
            throw new Zend_Image_Transform_Exception(
                'Both of middle and top was setted. Center and left can\'t ' .
                    'be setted at same time'
            );
        }

        if( $this->_middle && $this->_bottomOffset > 0 ) {
            throw new Zend_Image_Transform_Exception(
                'Both of middle and bottom was setted. Center and right can\'t ' .
                    'be setted at same time'
            );
        }

    }

    /**
     * Calculate left offset to crop from
     *
     * @return int
     */
    private function _calcLeftOffset()
    {
        if ( $this->_leftOffset > 0 ) {
            $leftOffset = $this->_leftOffset;
        } else if ( $this->_rightOffset > 0 ) {
            $leftOffset = $this->getWidth() - $this->_rightOffset;
        } else {
            $leftOffset = 0;
        }

        return $leftOffset;
    }


    /**
     * Calculate top offset to crop from
     *
     * @return int
     */
    private function _calcTopOffset()
    {
        if ( $this->_topOffset > 0 ) {
            $topOffset = $this->_topOffset;
        } else if ( $this->_bottomOffset > 0 ) {
            $topOffset = $this->getHeight() - $this->_bottomOffset;
        } else {
            $topOffset = 0;
        }

        return $topOffset;
    }

    /**
     * Top coordinate to crop from
     *
     * @var int
     */
    private $_topOffset = 0;

    /**
     * Bottom coordinate to crop from
     *
     * @var int
     */
    private $_bottomOffset = 0;

    /**
     * Left coordinate to crop from
     *
     * @var int
     */
    private $_leftOffset = 0;

    /**
     * Right coordinate to crop from
     *
     * @var int
     */
    private $_rightOffset = 0;

    /**
     * Is center (by x axis) used to crop
     *
     * @var int
     */
    private $_center = false;

    /**
     * Is center (by y axis) used to crop
     *
     * @var int
     */
    private $_middle = false;
}
