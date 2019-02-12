<!-- select2 from ajax -->
@php
    $old_group_id = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? false;
    $unioncloud = resolve('Twigger\UnionCloud\API\UnionCloud');
    if ($old_group_id)
    {
        $group = $unioncloud->groups()->getByID($old_group_id)->get()->first();
    }
@endphp

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>

    <div class="input-group" style="width: 80%">
        <div style="display: flex; width: 100%">
        <select
                name="{{ $field['name'] }}"
                style="width: 100%"
                id="uc_grp_sel_{!! $field['name'] !!}"
        >
        </select>

        <span class="input-group-btn">
            <button
                    class="btn btn-secondary"
                    type="button"
                    id="uc_refresh_grps_{!! $field['name'] !!}"
            >Refresh <i class="fa fa-refresh" id="refresh_uc_grp_{!! $field['name'] !!}"></i></button>
        </span>
        </div>
    </div>


    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}

    {{-- FIELD JS - will be loaded in the after_scripts section --}}

@endif

<!-- include field specific select2 js-->
@push('crud_fields_scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
        jQuery(document).ready(function($) {
            $('#uc_refresh_grps_{!! $field['name'] !!}').on('click', function($event){
                reloadGroups();
            });

            function reloadGroups() {
                $("#refresh_uc_grp_{!! $field['name'] !!}").addClass('fa-spin');
                /* Clear all options from the select list */
                $('#uc_grp_sel_{!! $field['name'] !!}').val(null).trigger('change');
                /* Get all groups */
                $.ajax({
                    url: '@php config('app.url') @endphp/unioncloud/get-all-groups',
                })
                .done(function(data) {
                    try {
                        options = [];
                        options.push($('<option>', {
                            value: '',
                            text : 'No Group Selected'
                        }));                        parsedData = JSON.parse(data);
                        for(i=0;i<parsedData.length;i++){
                            options.push($('<option>', {
                                value: parsedData[i].id,
                                text : parsedData[i].name
                            }));
                        }

                        $('#uc_grp_sel_{!! $field['name'] !!}').append(options).trigger('change');
                        $("#refresh_uc_grp_{!! $field['name'] !!}").removeClass('fa-spin');
                        @if($old_group_id)
                        $('#uc_grp_sel_{!! $field['name'] !!}').val({{$old_group_id}});
                        @endif
                    } catch (e) {
                        //TODO popup error message
                        console.log('Error getting groups');
                        $("#refresh_uc_grp_{!! $field['name'] !!}").removeClass('fa-spin');
                    }

                })
                .fail(function() {
                    //TODO popup error message
                    console.log('Error getting groups');
                    $("#refresh_uc_grp_{!! $field['name'] !!}").removeClass('fa-spin');
                });
            }

            $('#uc_grp_sel_{!! $field['name'] !!}').select2({
                placeholder: 'No group selected.'
            });
            reloadGroups();
        });
    </script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
