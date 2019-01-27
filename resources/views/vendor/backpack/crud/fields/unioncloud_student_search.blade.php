<!-- select2 from ajax -->
@php
    $old_student_uid = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? false;
    /** @var \Twigger\UnionCloud\API\UnionCloud $unioncloud */
    $unioncloud = resolve('Twigger\UnionCloud\API\UnionCloud');
    if ($old_student_uid)
    {
        /** @var \Twigger\UnionCloud\API\Resource\User $student */
        $student = $unioncloud->users()->setMode('standard')->getByUID($old_student_uid)->get()->first();
    }
@endphp

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>

    <div class="input-group" style="width: 80%">
        <div style="display: flex; width: 100%">
            <input
                    type="text"
                    style="width: 100%"
                    id="uc_sdnt_search_fname_{!! $field['name'] !!}"
                    placeholder="Forename..."
            >
            <input
                    type="text"
                    style="width: 100%"
                    id="uc_sdnt_search_sname_{!! $field['name'] !!}"
                    placeholder="Surname..."
            >

            <span class="input-group-btn">
                <button
                        class="btn btn-secondary"
                        type="button"
                        id="uc_refresh_sdnt_{!! $field['name'] !!}"
                >Search <i class="fa fa-refresh" id="refresh_uc_sdnt_i_{!! $field['name'] !!}"></i></button>
            </span>
        </div>

        <select
                name="{{ $field['name'] }}"
                style="width: 100%"
                id="uc_sdnt_sel_{!! $field['name'] !!}"
        >
        </select>
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
            $('#uc_refresh_sdnt_{!! $field['name'] !!}').on('click', function($event){
                search_data = {};
                if($('#uc_sdnt_search_fname_{!! $field['name'] !!}').val() !== ''){
                    search_data.forename = $('#uc_sdnt_search_fname_{!! $field['name'] !!}').val();
                }
                if($('#uc_sdnt_search_sname_{!! $field['name'] !!}').val() !== ''){
                    search_data.surname = $('#uc_sdnt_search_sname_{!! $field['name'] !!}').val();
                }

                if(search_data.hasOwnProperty('forename') || search_data.hasOwnProperty('surname'))
                {
                    searchStudents(search_data);
                } else {
                    console.log('Please give some data to search for');
                    // TODO Error message: Please give some data to search for
                }
            });

            function searchStudents(search_data) {
                $("#refresh_uc_sdnt_i_{!! $field['name'] !!}").addClass('fa-spin');
                /* Clear all options from the select list */
                $('#uc_sdnt_sel_{!! $field['name'] !!}').val(null).trigger('change');
                /* Search students */
                $.ajax({
                    url: '@php config('app.url') @endphp/unioncloud/search-students',
                    method: 'POST',
                    data: {parameters: search_data}
                })
                .done(function(data) {
                    try {
                        options = [];
                        parsedData = JSON.parse(data);
                        for(i=0;i<parsedData.length;i++){
                            options.push($('<option>', {
                                value: parsedData[i].uid,
                                text : parsedData[i].student_information
                            }));
                        }
                        $('#uc_sdnt_sel_{!! $field['name'] !!}').append(options).trigger('change');
                        $("#refresh_uc_sdnt_i_{!! $field['name'] !!}").removeClass('fa-spin');
                    } catch (e) {
                        //TODO popup error message
                        console.log('Error searching for students');
                        $("#refresh_uc_sdnt_i_{!! $field['name'] !!}").removeClass('fa-spin');
                    }

                })
                .fail(function() {
                    //TODO popup error message
                    console.log('Error searching for students');
                    $("#refresh_uc_sdnt_i_{!! $field['name'] !!}").removeClass('fa-spin');
                });
            }
            var initial_data = [];

            @if($old_student_uid)
                    initial_data.push({id: '{{$student->uid}}', text: '{{$student->forename}} {{$student->surname}} ({{$student->dob->format('d-m-Y')}}) - {{$student->uid}}'});
            @endif

            $('#uc_sdnt_sel_{!! $field['name'] !!}').select2({
                placeholder: 'Searching for Students...',
                data: initial_data
            });
        });
    </script>
@endpush
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}
