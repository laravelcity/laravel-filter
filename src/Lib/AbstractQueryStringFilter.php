<?php

namespace Laravelcity\Filter\Lib;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class AbstractQueryStringFilter
{
    protected $builder;
    protected $request;
    protected $filters;
    protected $fields = [];
    protected $hasFilter = 0;
    protected $baseClass;
    protected $mapMethodNames;

    public function __construct (Request $request , Builder $builder)
    {
        $this->request = $request;
        $this->builder = $builder;
        $this->addFieldsToBuilder();
        $this->baseClass = config('filter.input-class');

        $this->mapMethodNames['start_date'] = 'start_date';
        $this->mapMethodNames['end_date'] = 'end_date';
    }

    /**
     * apply filter to builder
     * @param null $builder
     * @return Builder|null
     */
    public function applyFilter ($builder = null)
    {
        if ($builder != null) {
            $this->builder = $builder;
            $this->addFieldsToBuilder();
        }

        $filters = $this->filters();
        $filters->each(function ($value) {
            $methodName = $this->getMethodName($value);
            $request = $this->request->get($value);
            if (method_exists($this , $methodName) && $request) {
                call_user_func([$this , $methodName] , $request);
                $this->hasFilter++;
            }
        });

        return $this->builder;
    }

    /**
     * @return Builder
     */
    public function builder ()
    {
        return $this->builder;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function filters ()
    {
        return collect($this->mapMethodNames)->keys();
    }

    /**
     * get method name from collect
     * @param $index
     * @return mixed
     */
    protected function getMethodName ($index)
    {
        $_index = strpos($index , '-') ? str_replace('-' , '_' , $index) : '';

        $indexFound = isset($this->mapMethodNames[$_index]) ? $_index : (isset($this->mapMethodNames[$index]) ? $index : false);

        if ($indexFound) {
            return $this->mapMethodNames[$indexFound];
        }

    }

    /**
     * add field for search form
     */
    private function addFieldsToBuilder ()
    {
        $this->builder->fields = $this->fields;
    }

    /**
     * filter created_at filed
     * @param $value
     */
    public function start_date ($value)
    {
        $dateF = switchDateWithLocale($value);//start date
        $this->builder->where(config('filter.created_date_filed') , '>=' , $dateF);
    }

    /**
     * filter created_at filed
     * @param $value
     */
    public function end_date ($value)
    {
        $dateE = switchDateWithLocale($value , 'date_end');//start date
        $this->builder->where(config('filter.created_date_filed') , '<=' , $dateE);
    }

    /**
     * add filed for search form
     * @param string $type
     * @param string $label
     * @param string $name
     * @param null $value
     * @param string $class
     * @param string $style
     * @param array $options
     */
    public function add ($type = 'text' , $label = 'label' , $name = 'name' , $value = null , $class = '' , $style = '' , $options = [])
    {
        $this->fields[] = [
            'type' => $type ,
            'name' => $name ,
            'id' => $name ,
            'label' => $label ,
            'value' => $value ,
            'class' => $this->baseClass . ' ' . $class ,
            'style' => $style ,
            'options' => $options
        ];

        $this->addFieldsToBuilder();

    }

    /**
     * return filter view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewFilters ()
    {
        return view('Filter::filters')->with(['filters' => $this->fields , 'hasFilter' => $this->hasFilter]);
    }

    abstract protected function baseQuery ();

}