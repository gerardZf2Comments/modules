<?php
namespace ZfModule\Options;
use Zend\Stdlib\AbstractOptions;

/**
 * Description of SearchOptions
 *
 * @author gerard
 */
class SearchOptions extends AbstractOptions
{
    /**
     *
     * @var int
     */
    protected $page;
    /**
     *
     * @var int
     */
    protected $limit;
    /**
     *
     * @var string
     */
    protected $query =null;
    /**
     *
     * @var string
     */
    protected $orderBy = null;
    /**
     *
     * @var string
     */
    protected $sort = 'ASC';
    /**
     * 
     * @param int $page
     * @param int $limit
     * @param string $query
     * @param string $orderBy
     * @param string $sort
     * @return \SearchOptions
     */
    public function __construct($page, $limit, $query = null, $orderBy = null, $sort = 'ASC') 
    {
        $this->setPage($page);
        $this->setLimit($limit);
        $this->setQuery($query);
        $this->setOrderBy($orderBy);
        $this->setSort($sort);
        
        return $this;
    }

    /**
     * 
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }
   
    /**
     * 
     * @param int $page
     * @return \SearchOptions
     */
    public function setPage($page)
    {
        $this->page = page;
        return $this;
    }
    /**
     * 
     * @return int||null
     */
    public function getLimit()
    {
        return $this->limit;
    }
    /**
     * 
     * @param int $limit
     * @return \SearchOptions
     */
    public function setLimit($limit)
    {
        $this->limit = $limit; 
        return $this;
    }
    /**
     * 
     * @return string||null
     */
    public function getQuery()
    {
        return $this->query;
    }
    /**
     * 
     * @param string $query
     * @return \SearchOptions
     */
    public function setQuery($query)
    {
        $this->query = $query; 
        return $this;
    }
   /**
    * 
    * @return string||null
    */
    public function getOrderBy()
    {
        return $this->orderBy;
    }
    /**
     * 
     * @param string $orderBy
     * @return \SearchOptions
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy; 
        return $this;
    }
    /**
     * 
     * @return string
     */
    public function getSort()
    {
        return $this->sort;
    }
    /**
     * 
     * @param string $sort
     * @return \SearchOptions
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
        return $this;
    }
}

