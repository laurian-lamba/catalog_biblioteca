<div class="w-100 emailfield mt-1">
    @php
        /* @var \App\Facades\Util $util */
        /* @var \App\Facades\Common $common */
    @endphp
    <form wire:submit.prevent="saveSubscriber">
        <div class="mb-2">
            <input type="email" name="email" wire:model.defer="email" required placeholder="Email">
            @error('email')
            <div class="w-100 txt_box_wrap">
                @include('back.common.validation', ['message' =>  $common::reformatErrorMsg($message),"cls_name" => "word-wrap"  ])
            </div>
            @enderror
        </div>
        <input class="submitbutton ripplelink btn_subscribe" type="submit" value="Subscribe">
    </form>
</div>
