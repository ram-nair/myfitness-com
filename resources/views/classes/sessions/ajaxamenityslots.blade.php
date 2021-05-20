<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        @if(isset($noData) && $noData)
            <div class="alert alert-dismissible">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <p>There is no slot details available right now</p>
            </div>
        @else
            @if(isset($unAvailableSlots) && $unAvailableSlots)
                <div class="alert alert-dismissible">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <p>These time slots are not available</p>
                    <ul>
                        @foreach ($unAvailableSlots as $error)
                            <li>
                                {{ $error->slots }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(isset($availableSlots) && $availableSlots)
                <div class="alert alert-dismissible">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <p>These time slots are booked</p>
                    <ul>
                        @foreach ($availableSlots as $error)
                            <li>
                                {{ $error->slots }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif
    </div>
</div>