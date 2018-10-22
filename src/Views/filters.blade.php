<div class="portlet search-panel">
    <div class="portlet-heading portlet-default">
        <h3 class="portlet-title text-dark"><i class="glyphicon glyphicon-search"></i>
            @lang('Filter::filter.search')
        </h3>
        <div class="portlet-widgets">
            <a @if($hasFilter<=0) class="collapsed" @endif data-toggle="collapse" data-parent="#accordion1"
               href="#bg-default" @if($hasFilter<=0) aria-expanded="false" @else aria-expanded="true" @endif ><i
                        class="ion-minus-round"></i></a>
        </div>
        <div class="clearfix"></div>
    </div>
    <div id="bg-default" class="panel-collapse form-inline collapse @if($hasFilter>0)  show @endif ">
        <form method="get" action="{{request()->fullUrl()}}">
            <div class="portlet-body filter-body">

                @forelse($filters as $filter)
                    <?php
                    $data = [];
                    if (isset($filter['data'])) {
                        $data = $filter['data'];
                    }
                    ?>
                    <div style="max-width: 100%;margin: 0 5px;margin-bottom: 15px;"
                         class="form-group mr-10 {{rightOrLeft('float')}}">
                        <label style="margin: 0  5px">{{@$filter['label']}}</label>
                        @if(@$filter['type']=='text')
                            <input style="{{@$filter['style']}}" name="{{@$filter['name']}}" id="{{@$filter['name']}}"
                                   type="text" class="{{@$filter['class']}}"
                                   value="{{@$filter['value']?:\Illuminate\Support\Facades\Input::get($filter['name'])}}">
                        @endif
                        @if($filter['type']=='select')
                            {!! Form::select($filter['name'],@$filter['value'],\Illuminate\Support\Facades\Input::get($filter['name'],null),array_merge($data,['class'=>@$filter['class'],'id'=>@$filter['name']])) !!}
                        @endif
                        @if($filter['type']=='select2')
                            @include('components.select2')

                            {!! Form::select(@$filter['name'],@$filter['value'],null,['class'=>@$filter['class'].' ajax-data ','id'=>@$filter['name'],'multiple'=>isset($multiple)?'multiple':'false']) !!}

                        @section('js') @parent
                            <script>
                            $(function () {
                                $('.ajax-data').select2({
                                    placeholder: '{{@$filter['options']['placeholder']}}',
                                    tags: {{@$filter['options']['data']}},
                                    ajax: {
                                        url: '{{@$filter['options']['url']}}',
                                        dataType: 'json',
                                        delay: 0,
                                        minimumInputLength:{{@$filter['options']['minimumInputLength']}},
                                        language: 'fa',
                                        data: function (params) {
                                            return {
                                                q: $.trim(params.term), // search term
                                                page: params.page
                                            };
                                        },
                                        processResults: function (data) {
                                            return {
                                                results: data
                                            };
                                        },
                                        cache: true
                                    }
                                })
                            })
                        </script>
                        @stop
                        @endif
                        @if($filter['type']=='date')
                            <div class="input-daterange input-group" id="date-range">
                                <input type="text" placeholder=""
                                       value="{{\Illuminate\Support\Facades\Input::get('start_date')}}"
                                       class="{{@$filter['class']}} datepicker" name="start_date" id="start_date">
                                <span class="input-group-addon bg-custom b-0 text-white btn-sm">to</span>
                                <input type="text" placeholder=""
                                       value="{{\Illuminate\Support\Facades\Input::get('end_date')}}"
                                       class="{{@$filter['class']}} datepicker" name="end_date" id="end_date">
                            </div>

                            @include('components.datepicker')
                        @endif

                    </div>
                @empty

                @endforelse

                <div style="margin-bottom: 15px;max-width: 100%;" class="form-group mr-10 {{rightOrLeft('float')}}">
                    <label style="max-width: 100% !important;display: block;margin-top: -7px;">&nbsp;</label>

                    <button style="margin: 0 5px;" class="brn btn-white btn-sm" type="submit"><i
                                class="glyphicon glyphicon-search"></i> @lang('Filter::filter.search')</button>
                    <a href="{{request()->url()}}" class="brn btn-white btn-sm">@lang('Filter::filter.reset')</a>
                </div>
                <div class="clearfix"></div>
            </div>
        </form>

    </div>
</div>