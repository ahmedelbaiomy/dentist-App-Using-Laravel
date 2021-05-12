<ul class="list-group">
    @foreach($categories as $cat)
    <li class="list-group-item">
        @if($cat->path_icon)
        <p class="mb-0 text-center"><img style="height:60px" src="{{asset(base64_decode($cat->path_icon))}}" class="" /></p>
        @endif
        <p class="mb-0"><span> {{$cat->name}}</span></p>
        <p class="mb-0"><span> {{$cat->name_ar}}</span></p>
        <p class="mb-0"><span class="badge badge-light-primary badge-pill mr-1">{{$cat->services->count()}} services</span>
        @if($cat->is_active==0)
        <span class="badge badge-light-danger badge-pill">disabled</span>
        @endif    
        </p>
        <p class="mt-1 mb-0">
        <div class="btn-group">
            <button class="btn btn-icon btn-sm btn-outline-primary" onclick="_loadDatasByCategory({{$cat->id}})"
                title="View services">{!!\App\Library\Helpers\Helper::getSvgIconeByAction('VIEW')!!}</button>
            <button class="btn btn-icon btn-sm btn-outline-primary" onclick="_formCategory({{$cat->id}})"
                title="Edit">{!!\App\Library\Helpers\Helper::getSvgIconeByAction('EDIT')!!}</button>
            @if($cat->services->count()==0)
            <button type="button" onclick="_deleteCategory({{ $cat->id }})"
                class="btn btn-icon btn-sm btn-outline-danger">{!!\App\Library\Helpers\Helper::getSvgIconeByAction('DELETE')!!}</button>
            @endif
        </div>
        </p>
    </li>
    @endforeach
</ul>