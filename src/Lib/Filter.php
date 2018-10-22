<?php

namespace Laravelcity\Filter\Lib;

class Filter
{
    protected $baseClass;
    protected $filters;

    public function __construct ()
    {
        $this->baseClass = config('filter.input-class');
    }

    /**
     * add fields to array list
     * @param string $type
     * @param string $label
     * @param string $name
     * @param string $id
     * @param null $value
     * @param string $class
     * @param string $style
     * @param array $options
     */
    public function add ($type = 'text' , $label = 'label' , $name = 'name' , $id = 'id' , $value = null , $class = '' , $style = '' , $options = [])
    {
        $this->filters[] = [
            'type' => $type ,
            'name' => $name ,
            'id' => $id ,
            'label' => $label ,
            'value' => $value ,
            'class' => $this->baseClass . ' ' . $class ,
            'style' => $style ,
            'options' => $options
        ];
    }

    /**
     * return filter view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function make ()
    {
        return view('Filter::filters')->with(['filters' => $this->filters]);
    }

}