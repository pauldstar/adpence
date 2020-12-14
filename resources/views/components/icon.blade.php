@props([
    'name',
    'class',
    'color' => 'currentColor',
    'width' => 32,
    'height' => 32,
])

<svg class="bi {{ $class }}" width="{{ $width }}" height="{{ $height }}" fill="{{ $color }}">
    <use xlink:href="{{ asset('icon/bootstrap-icons.svg#') . $name }}"/>
</svg>
