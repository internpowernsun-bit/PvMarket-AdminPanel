@props([
    'name'         => 'image',
    'label'        => 'Image',
    'currentImage' => null,
    'hint'         => 'Leave blank to keep current image',
    'accept'       => 'image/*',
    'required'     => false,
])

<div class="form-group">
    <label class="form-label">
        {{ $label }}@if($required)<span>*</span>@endif
    </label>

    @if($currentImage)
        <img src="{{ asset('storage/' . $currentImage) }}"
             class="img-preview" alt="Current Image"/>
    @endif

    <div class="form-file-wrap">
        <input
            type="file"
            name="{{ $name }}"
            accept="{{ $accept }}"
            {{ $required && !$currentImage ? 'required' : '' }}
        />
    </div>

    @if($currentImage)
        <span class="form-hint">{{ $hint }}</span>
    @endif
</div>