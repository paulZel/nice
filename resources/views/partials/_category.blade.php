@if($post->hasCategory())
    <h6><a href="{{route('category.show', $post->category->slug)}}">{{$post->getCategoryTitle()}}</a></h6>
@else
    <h6 style="color: #00acdf; font-weight: 700; font-size: 10px;">No category</h6>
@endif
