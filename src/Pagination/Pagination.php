<?php

namespace Overphp\LaravelSkeleton\Pagination;

final class Pagination
{
    private const PAGE_FIELD = 'page';
    private const PAGE_SIZE_FIELD = 'page_size';
    public const MAX_PAGE_SIZE = 100;
    public const PAGE_SIZE = 20;
    public const PAGE = 1;

    protected string $page_filed;
    protected string $page_size_filed;
    protected int $max_page_size = 100;
    protected int $page_size = self::PAGE_SIZE;
    protected int $page = self::PAGE;
    protected int $total_page = 0;
    protected int $total = 0;

    /**
     * @param int $total
     * @param int $page_size
     * @param int $max_page_size
     * @param string $page_filed
     * @param string $page_size_filed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(
        int $total,
        int $page_size = self::PAGE_SIZE,
        int $max_page_size = self::MAX_PAGE_SIZE,
        string $page_filed = self::PAGE_FIELD,
        string $page_size_filed = self::PAGE_SIZE_FIELD
    ) {
        $this->max_page_size = $max_page_size;
        $this->page_size = $page_size;
        $this->page_filed = $page_filed;
        $this->page_size_filed = $page_size_filed;

        $this->paginate($total);
    }

    /**
     * @param int $total
     * @return $this
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function paginate(int $total): self
    {
        // 1. 总数量
        $this->total = max($total, 0);

        // 2. 单页数量
        $page_size = !empty($this->page_size_filed) ?
            intval(request()->get($this->page_size_filed, $this->page_size)) :
            $this->page_size;
        $page_size = max($page_size, 1);
        $this->page_size = min($page_size, $this->max_page_size);

        // 3. 总页数
        $this->total_page = ceil($this->total / $this->page_size);

        // 4. 当前页码
        $page = !empty($this->page_filed) ?
            intval(request()->get($this->page_filed, self::PAGE)) :
            self::PAGE;
        $page = min($page, $this->total_page);
        $this->page = max($page, self::PAGE);

        return $this;
    }

    /**
     * Number of queries skipped
     *
     * @return int
     */
    public function offset(): int
    {
        return ($this->page - 1) * $this->page_size;
    }

    /**
     * Query quantity
     *
     * @return int
     */
    public function limit(): int
    {
        return $this->page_size;
    }

    /**
     * Alias of offset()
     *
     * @return int
     */
    public function skip(): int
    {
        return $this->offset();
    }

    /**
     * Alias of limit()
     *
     * @return int
     */
    public function take(): int
    {
        return $this->limit();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'total' => $this->total, // 数据总量
            'total_page' => $this->total_page, // 页码总数
            'page' => $this->page, // 当前页码 默认1
            'page_size' => $this->page_size, // 页码总数
        ];
    }
}
