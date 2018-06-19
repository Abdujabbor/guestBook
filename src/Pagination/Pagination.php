<?php
/**
 * Created by PhpStorm.
 * User: abdujabbor
 * Date: 6/19/18
 * Time: 1:58 PM
 */

namespace Pagination;
/**
 * Class Pagination
 * @package Pagination
 */
class Pagination
{
    private $baseURL;
    private $total;
    private $pagesCount;
    private $currentPageParam;
    private $itemsPerPage;
    private $currentPage;
    private $containerTag = 'ul';
    private $itemTag = 'li';
    private $itemClass = 'page-item';
    private $activeItemClass = 'current';

    /**
     * Pagination constructor.
     * @param string $baseURL
     * @param int $total
     * @param int $itemsPerPage
     * @param string $currentPageParam
     * @throws \Exception
     */
    public function __construct(string $baseURL, int $total = 0, int $itemsPerPage = 0, $currentPageParam = 'page')
    {
        if (!filter_var($baseURL, FILTER_VALIDATE_URL)) {
            throw new \Exception("Invalid url param");
        }

        $this->baseURL = $baseURL;

        if ($total <= 0) {
            throw new \Exception("Total number cannot be less than zero");
        }
        if ($itemsPerPage <= 0) {
            throw new \Exception("Items per page cannot be less than zero");
        }

        $this->total = $total;
        $this->itemsPerPage = $itemsPerPage;
        $this->currentPageParam = $currentPageParam;
        $this->currentPage = isset($_GET[$this->currentPageParam]) && intval($_GET[$this->currentPageParam]) &&
        $_GET[$this->currentPageParam] > 0 ? ($_GET[$this->currentPageParam] - 1) : 1;

        $this->pagesCount = intval($this->total / $this->itemsPerPage);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $response = "";
        $response .= "<{$this->containerTag}>";
        $response .= $this->renderPreviousArrow();
        for ($i = 1; $i <= $this->pagesCount; $i++) {
            $response .= $this->renderItem($i);
        }
        $response .= $this->renderNextArrow();
        $response .= "</{$this->containerTag}>";
        return $response;
    }

    public function renderPreviousArrow(): string
    {
        $response = "";
        if ($this->currentPage >= 1) {
            $response .= $this->renderItem($this->currentPage, '<');
        }
        return $response;
    }

    public function renderNextArrow(): string
    {
        $response = "";
        if ($this->currentPage + 2 <= $this->pagesCount) {
            $response .= $this->renderItem($this->currentPage + 2, '>');
        }
        return $response;
    }

    /**
     * @param int $page
     * @return string
     */
    public function renderItem(int $page, $symbol = ''): string
    {
        if (!$symbol) {
            $symbol = "{$page}";
        }
        $itemClass = $this->itemClass;
        if ($this->currentPage === ($page - 1)) {
            $itemClass .= " {$this->activeItemClass}";
        }


        $url = $this->generateItemURL();

        $response = sprintf("<{$this->itemTag} class='%s'><a href='{$url}'>%s</a></{$this->itemTag}>",
            $itemClass, $this->currentPageParam, $page, $symbol);
        return $response;
    }

    public function generateItemURL()
    {
        $url = "{$this->baseURL}?%s=%s";

        if (!empty($_SERVER['QUERY_STRING'])) {
            $params = [];
            parse_str($_SERVER['QUERY_STRING'], $params);
            if(isset($params['page'])) {
                unset($params['page']);
            }
            if(count($params) > 0) {
                $url = $this->baseURL . '?' . http_build_query($params);
                $url .= '&%s=%s';
            } else {
                $url = "?%s=%s";
            }
        }
        return $url;
    }

}