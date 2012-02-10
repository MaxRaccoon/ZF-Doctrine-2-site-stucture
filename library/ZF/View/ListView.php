<?php
/**
 * User: raccoon
 * Date: 10.02.12 10:15
 */
namespace ZF\View;
class ListView
{
    private $_controllerName,
            $_titles,
            $_mainTitle,
            $_listData = array();
    
    function __construct($controller_name, array $titles, array $data)
    {
        $this->_controllerName = $controller_name;
        $this->setTitles($titles);
        $this->setData($data);
    }

    /**
     * @param  $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->_mainTitle = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_mainTitle;
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return $this->_controllerName;
    }

    /**
     * @param  $titles
     * @return void
     */
    public function setTitles($titles)
    {
        $this->_titles = $titles;
    }

    /**
     * @return array
     */
    public function getTitles()
    {
        return $this->_titles;
    }

    /**
     * @param  $data
     * @return void
     */
    public function setData($data)
    {
        $i = 0;
        foreach ( $data AS $row )
        {
            foreach ( $this->_titles AS $key=>$value )
            {
                if (substr($key, 0, 1) == "#")
                {
                    switch (substr($key, 1))
                    {
                        case "edit":
                            {
                                $this->_listData[$i][$key] = array("controller"=>$this->_controllerName,
                                                                   "action"=>"edit",
                                                                    "ID"=>$row->getId()
                                                                    );
                            }
                        break;
                    }
                }
                else
                {
                    $getFunc = "get" . ucfirst($key);
                    if ( is_callable(array($row, $getFunc)) )
                    {
                        $this->_listData[$i][$key] = $row->$getFunc();
                    }
                    else
                    {
                        $this->_listData[$i][$key] = null;
                    }
                }
            }
            $i++;
        }
    }

    /**
     * return array
     */
    public function getList()
    {
        return $this->_listData;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->_listData);
    }
}
