<!-- start page title -->
@php
    $title_level = 1;
    if(getPageConfig()){
        if( isset(getPageConfig()['tree_level'])){
            $title_level = getPageConfig()['tree_level'];
        }
    }
@endphp
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center ">
            <h4 class="mb-sm-0 font-size-18">{{ $title }} 
            @if (getPageTitle()!="")
                <h4 class="mb-sm-0 font-size-18"> > </h4>
                <div class="px-2">
                    <ol class="breadcrumb m-0">
                        <a href="{{ getRoute(1) }}" > {{ getPageTitle() }} </a> 
                    </ol>
                </div>
            @endif
            @for($i=1;$i<=$title_level;$i++)
                @if (getBreadcrumbTitle($i))
                    <h4 class="mb-sm-0 font-size-18"> > </h4>
                    <div class="px-2">
                        <ol class="breadcrumb m-0">
                            @if($i<$title_level)
                                <a href="{{ getRoute($i) }}" > {{ getBreadcrumbTitle($i) }} </a>
                            @else
                                {{ getBreadcrumbTitle($i) }}
                            @endif
                        </ol>
                    </div>
                @endif
            @endfor
        </div>
    </div>
</div>
<!-- end page title -->
