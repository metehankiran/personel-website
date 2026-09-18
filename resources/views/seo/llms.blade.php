# {!! $title !!}

@if(filled($summary))
> {!! \Illuminate\Support\Str::of(strip_tags($summary))->squish() !!}

@endif
## Sayfalar

@foreach($pages as $label => $url)
- [{!! $label !!}]({{ $url }})
@endforeach
@if($areas->isNotEmpty())

## Hizmet Bölgeleri

@foreach($areas as $area)
- [{!! $area->name !!}]({{ \App\Support\Seo::route('service-areas.show', $area) }})@if(filled($area->summary)): {!! \Illuminate\Support\Str::of(strip_tags($area->summary))->squish()->limit(160) !!}@endif

@endforeach
@endif
@if($projects->isNotEmpty())

## Projeler

@foreach($projects as $project)
- [{!! $project->title !!}]({{ \App\Support\Seo::route('projects.show', $project) }})@if(filled($project->description)): {!! \Illuminate\Support\Str::of(strip_tags($project->description))->squish()->limit(160) !!}@endif

@endforeach
@endif
@if($posts->isNotEmpty())

## Yazılar

@foreach($posts as $post)
- [{!! $post->title !!}]({{ \App\Support\Seo::route('blog.show', $post) }})@if(filled($post->excerpt)): {!! \Illuminate\Support\Str::of(strip_tags($post->excerpt))->squish()->limit(160) !!}@endif

@endforeach
@endif
