@props(['status'])

@if ($status)

    <div style="color: #ffffff;width: fit-content;">
        @switch($status)
            @case(1)
                <span class="w-full" style="padding: .125rem .725rem;border-radius: 9px;background-color: darkred;">Ожидает оплаты</span>
                @break
            @case(2)
                <span class="w-full" style="padding: .125rem .725rem;border-radius: 9px;background-color: green;">Оплачено</span>
                @break
            @case(3)
                <span class="w-full" style="padding: .125rem .725rem;border-radius: 9px;background-color: darkblue;">Продано</span>
                @break
        @endswitch
    </div>

@endif
