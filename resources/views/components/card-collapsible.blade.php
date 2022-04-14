@props([
    'title' => '',
    'col' => 12,
    'collapse' => false,
    'color' => 'primary',
])

<div class="col-md-{{ $col }}">
    <div class="card card-{{ $color }} @if($collapse) collapsed-card @endif">
      <div class="card-header">
        <h3 class="card-title">{{ $title }}</h3>

        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse" aria-expanded="false">
            <i class="fas fa-{{ $collapse ? 'plus' : 'minus' }}"></i>
          </button>
        </div>
      </div>
      <div class="card-body" @if($collapse) style="display: none;" @endif>
        {{ $slot }}
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>