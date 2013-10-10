<?php
namespace ZfModule\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * options object for search configurations
 *@todo review posibility of mering with general config
 *@todo pretty sure the abstract class uses classmethods to hydrate from array
 * @author gerard
 */
class SearchOptions extends AbstractOptions
{
    /**
     *the page number
     * @var int
     */
    protected $page;
    /**
     *items per page
     * @var int
     */
    protected $limit;
    /**
     *search query
     * @var string
     */
    protected $query =null;
    /**
     *order
     * @var string
     */
    protected $orderBy = null;
    /**
     *sort
     * @var string
     */
    protected $sort = 'ASC';
    /**
     * set the options for search
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
     * current page
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }
   
    /**
     * cureent page
     * @param int $page
     * @return \SearchOptions
     */
    public function setPage($page)
    {
        $this->page = page;
       
        return $this;
    }
    /**
     * limit
     * @return int||null
     */
    public function getLimit()
    {
        return $this->limit;
    }
    /**
     * limit
     * @param int $limit
     * @return \ZfModule\Options\SearchOptions
     */
    public function setLimit($limit)
    {
        $this->limit = $limit; 
        
        return $this;
    }
    /**
     * the query
     * @return string||null
     */
    public function getQuery()
    {
        return $this->query;
    }
    /**
     * query
     * @param string $query
     * @return \SearchOptions
     */
    public function setQuery($query)
    {
        $this->query = $query; 
        
        return $this;
    }
   /**
    * orderby
    * @return string||null
    */
    public function getOrderBy()
    {
        return $this->orderBy;
    }
    /**
     * order by
     * @param string $orderBy
     * @return \SearchOptions
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy; 
        
        return $this;
    }
    /**
     * sort
     * @return string
     */
    public function getSort()
    {
        return $this->sort;
    }
    /**
     * sort
     * @param string $sort
     * @return \SearchOptions
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
        
        return $this;
    }
}

