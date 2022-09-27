<div id="e-signpad" style="display: flex; flex-direction: column; align-items: center">
    <canvas style="touch-action: none; border: 1px solid {{ $borderColor }}"
            width="{{ $width }}"
            height="{{ $height }}"
            class="{{ $padClasses }}"></canvas>
    <div>
        <input type="hidden" name="sign" id="sign">
        <button id="sign-pad-button-clear" type="button" class="{{$buttonClasses}}"> {{$clearName}}</button>
        <button id="sign-pad-button-submit" type="submit" class="{{$buttonClasses}}"> {{$submitName}}</button>
    </div>
</div>

