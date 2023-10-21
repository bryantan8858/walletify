<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha256-OFRAJNoaD8L3Br5lglV7VyLRf0itmoBzWUoM+Sji4/8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/filter_multi_select.css') }}" />
    <script src="{{ asset('js/filter-multi-select-bundle.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/modal.css') }}">
    <script src="{{ asset('js/modal.js') }}"></script>
</head>
<x-app-layout>
    <main class="flex">
        <aside class="fixed left-0 z-40 transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar"
            style="border-right: 2px solid white; width: 20%; height:100vh; margin-top: 65px;">
            <div class="h-full px-3 py-4 overflow-y-auto" style="background-color: #92C3E3">
                <ul class="space-y-2 font-medium">
                    <li>
                        <div class="flex items-center p-2 text-gray-900 rounded-lg dark:text-black mx-auto">
                            <span href="{{ route('budget.index') }}" class="mx-auto"
                                style="font-size:24px;"><b>Budget</b></span>
                        </div>
                    </li>
                    <div class="flex justify-center">
                        <button class="justify-center rounded text-white createBudgetBtn"
                            style="background: #4D96EB; width: 155px; height: 30px" data-toggle="modal"
                            data-target="#budgetTemplateSelectionModal">
                            <i class="far fa-plus-square mr-1" style="color: #ffffff;"></i>
                            <span>Create Budget</span>
                        </button>
                    </div>
                </ul>
            </div>
        </aside>
        <div class="flex  p-4 sm:ml-64 items-center justify-center"
            style="width: 80%; margin-left:20%; margin-top: 120px;">
            @if ($budgetData && count($budgetData) > 0)
                @foreach ($budgetData as $budgetIndex => $budget)
                    <div class="bg-white" style="width: 60%; height:auto; border-radius:15px; padding:50px 80px;">
                        <div class="flex justify-between mb-5">
                            <p class="text-gray-400">Current month</p>
                            <p>Allocation amount: RM {{ $budget['total_allocation_amount'] }}</p>
                        </div>
                        @foreach ($budget['parts'] as $partIndex => $part)
                            <div class="text-base font-medium dark:text-black flex justify-between">
                                <label for="part-name">{{ $part['part_name'] }}
                                    <button
                                        data-tooltip-target="tooltip-default-{{ $budgetIndex }}-{{ $partIndex }}"
                                        type="button" class="focus:ring-4 focus:outline-none focus:ring-gray-300 ">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </button>
                                    <div id="tooltip-default-{{ $budgetIndex }}-{{ $partIndex }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        @foreach ($part['category_names'] as $categoryName)
                                            <div>{{ $categoryName }}</div>
                                        @endforeach
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                </label>
                                <div>
                                    RM {{ $part['current_budget'] }} /
                                    RM {{ $part['allocation_amount'] }}
                                    ({{ round($part['percentage'], 2) }}%)
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4 mb-4">
                                <div class="bg-{{ $part['percentage_for_width'] >= 80 ? 'red' : 'green' }}-400 h-4 rounded-full"
                                    style="width: {{ $part['percentage_for_width'] }}%">
                                </div>
                            </div>
                        @endforeach
                        {{-- button --}}
                        <div class="float-right mt-3">
                            @if ($budget['budget']->template_name == 'Default Template')
                                <button type="button"
                                    class="bg-blue-500 w-20 rounded editDefaultTemplateBtn">Edit</button>
                            @else
                                <button type="button"
                                    class="bg-blue-500 w-20 rounded editUserTemplateBtn">Edit</button>
                            @endif
                            <button type="button" class="bg-red-500 w-20 rounded ml-2 deleteBudgetBtn"
                                onclick="budgetDeleteModal({{ $budget['budget']->budget_id }})">Delete</button>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="m-3 flex justify-center">No budget found.</p>
            @endif
        </div>
    </main>
    @include('budget.createUserTemplate')
    @include('budget.createDefaultTemplate')
</x-app-layout>

{{-- budget template selection modal --}}
<div id="budgetTemplateSelectionModal" class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="budgetTemplateSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog relative p-4 w-full  h-full md:h-auto" role="document">
        {{-- Modal Content --}}
        <div class="modal-content-m relative p-4 text-center bg-white rounded-lg shadow sm:p-5">
            <div class="modal-header flex justify-between items-center pb-3 rounded-t">
                <h2 style="font-size:20px; margin-left:auto; margin-right:auto">Choose one from the selection below</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body flex justify-evenly my-10">
                <button class="bg-blue-500 rounded-md createTemplate" style="width: 250px; height: 240px"
                    data-template-type="user_defined">Create a new
                    template?</button>
                <button class="bg-green-500 rounded-md defaultTemplate" style="width: 250px; height: 240px"
                    data-template-type="default">Apply the
                    default template</button>
            </div>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content-s relative p-4 text-center bg-white rounded-lg shadow sm:p-5">
            <div class="modal-header flex justify-end">
                <button type="button" class="close " data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body flex flex-col items-center">
                Are you sure you want to delete this budget?
            </div>
            <div class="flex justify-center items-center space-x-4">
                @if (isset($budget))
                    <form id="deleteForm" method="POST"
                        action="{{ route('budget.delete', ['budget' => $budget['budget']->budget_id]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="width: 120px"
                            class="py-2 px-3 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 mt-4">Yes</button>
                    </form>
                @endif
                <button type="button" style="width: 120px"
                    class="py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-gray-900 focus:z-10"
                    data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-red-400 {
        background-color: #f03535;
        /* Red color */
    }

    .bg-green-400 {
        background-color: #6fec6f;
        /* Green color */
    }
</style>
