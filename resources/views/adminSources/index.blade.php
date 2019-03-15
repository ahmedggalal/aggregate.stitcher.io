@php
    /** @var \Domain\User\Models\User $user */
    /** @var \Domain\Source\Models\Source[]|\Illuminate\Pagination\LengthAwarePaginator $sources */
@endphp

@component('layouts.admin', [
    'title' => __('Sources'),
])
    <div class="flex justify-between">
        <heading>{{ __('Sources') }}</heading>

        <search-field
            :current-url="$currentUrl"
            :current-search-query="$currentSearchQuery"
        ></search-field>
    </div>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>
                    <sort-link name="url">
                        {{ __('Name') }}
                    </sort-link>
                </th>
                <th>
                    <sort-link name="is_validated">
                        {{ __('Feed') }}
                    </sort-link>
                </th>
                <th class="text-right">
                    <sort-link name="post_count">
                        {{ __('Posts') }}
                    </sort-link>
                </th>
                <th class="text-right">
                    <sort-link name="created_at">
                        {{ __('Date created') }}
                    </sort-link>
                </th>
                <th class="text-right">
                    <filter-button
                        name="is_active"
                        value="0"
                    >
                        {{ __('Inactive sources') }}
                    </filter-button>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sources as $source)
                <tr>
                    <td>
                        <a
                            class="
                                link
                                inline-block
                                mt-1
                            "
                            href="{{ action([\App\Http\Controllers\AdminSourcesController::class, 'edit'], $source) }}"
                        >{{ $source->website }}</a>

                        <div class="mt-2">
                            <a
                                class="link text-grey-darker"
                                href="{{ $source->url }}"
                            >
                                {{ __('RSS') }}</a>&nbsp;–
                            <a
                                class="link text-grey-darker"
                                href="{{ action([\App\Http\Controllers\PostsController::class, 'source'], $source->website) }}"
                            >
                                {{ __('Filtered') }}
                            </a>
                        </div>
                    </td>
                    <td>
                        @if($source->is_validated)
                            <a href="{{ $source->url }}" target="_blank" rel="noopener noreferrer" class="link">
                                {{ $source->url }}
                            </a>
                        @elseif($source->validation_failed_at)
                            <span class="text-red">
                                {{ __('No valid RSS found') }}
                            </span>
                        @else
                            <span class="text-grey-darker">
                                {{ __('Feed validation pending…') }}
                            </span>
                        @endif
                    </td>
                    <td class="text-right">
                        {{ $source->post_count }}
                    </td>
                    <td class="text-right">
                        {{ $source->created_at->toDateTimeString() }}
                    </td>
                    <td class="text-right">
                        @if ($source->is_active)
                            <span class="text-green font-bold">
                                {{ __('Active') }}
                            </span>
                        @elseif($source->is_validated)
                            <post-button
                                class="button button-small button-green"
                                :action="action([\App\Http\Controllers\AdminSourcesController::class, 'activate'], $source->uuid)"
                            >
                                {{ __('Activate') }}
                            </post-button>
                        @endif

                        <a
                            href="{{ action([\App\Http\Controllers\AdminSourcesController::class, 'confirmDelete'], $source) }}"
                            class="mt-2 block"
                        >
                            {{ __('Delete') }}
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        <form-component
            :action="action([\App\Http\Controllers\AdminSourcesController::class, 'store'])"
            class="flex items-bottom justify-end"
        >
            <text-field
                name="url"
                label=""
            ></text-field>

            <submit-button class="ml-4 button-small">
                {{ __('Add source') }}
            </submit-button>
        </form-component>
    </div>

    {{ $sources->render() }}
@endcomponent
