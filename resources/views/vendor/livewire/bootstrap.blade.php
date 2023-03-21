<div>
	@if ($paginator->hasPages())
		@php(isset($this->numberOfPaginatorsRendered[$paginator->getPageName()]) ? $this->numberOfPaginatorsRendered[$paginator->getPageName()]++ : ($this->numberOfPaginatorsRendered[$paginator->getPageName()] = 1))

		<nav>
			<ul class="pagination pagination-sm justify-content-end">
				{{-- Previous Page Link --}}
				@if ($paginator->onFirstPage())
					<li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
						<span class="page-link bg-dark" aria-hidden="true"><i class="bi bi-arrow-left-circle"></i></span>
					</li>
				@else
					<li class="page-item">
						<button type="button"
							dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}"
							class="page-link bg-dark" wire:click="previousPage('{{ $paginator->getPageName() }}')"
							wire:loading.attr="disabled" rel="prev" aria-label="@lang('pagination.previous')"><i
								class="bi bi-arrow-left-circle"></i></button>
					</li>
				@endif

				{{-- Pagination Elements --}}
				@foreach ($elements as $element)
					{{-- "Three Dots" Separator --}}
					@if (is_string($element))
						<li class="page-item disabled" aria-disabled="true"><span class="page-link bg-dark">{{ $element }}</span>
						</li>
					@endif

					{{-- Array Of Links --}}
					@if (is_array($element))
						@foreach ($element as $page => $url)
							@if ($page == $paginator->currentPage())
								<li class="page-item active"
									wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-page-{{ $page }}"
									aria-current="page"><span class="page-link bg-dark">{{ $page }}</span></li>
							@else
								<li class="page-item"
									wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-page-{{ $page }}">
									<button type="button" class="page-link bg-dark"
										wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')">{{ $page }}</button>
								</li>
							@endif
						@endforeach
					@endif
				@endforeach

				{{-- Next Page Link --}}
				@if ($paginator->hasMorePages())
					<li class="page-item">
						<button type="button"
							dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}"
							class="page-link bg-dark" wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled"
							rel="next" aria-label="@lang('pagination.next')"><i class="bi bi-arrow-right-circle"></i></button>
					</li>
				@else
					<li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
						<span class="page-link bg-dark" aria-hidden="true"><i class="bi bi-arrow-right-circle"></i></span>
					</li>
				@endif
			</ul>
		</nav>
	@endif
</div>
