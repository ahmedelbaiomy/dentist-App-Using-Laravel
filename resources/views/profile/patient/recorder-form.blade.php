<div class="row">
    <div class="col-md-12">
        <h6 class="card-title">Recorder</h6>
        <!-- recoder -->
        <div id="controls">
            <button id="recordButton" type="button" data-toggle="tooltip" data-placement="top" title="Recorde"
                class="btn btn-icon btn-outline-primary">
                {!!\App\Library\Helpers\Helper::getSvgIconeByAction('MIC')!!}
            </button>
            <button id="pauseButton" type="button" data-toggle="tooltip" data-placement="top" title="Pause"
                class="btn btn-icon btn-outline-primary" disabled>
                {!!\App\Library\Helpers\Helper::getSvgIconeByAction('PAUSE')!!}
            </button>
            <button id="stopButton" type="button" data-toggle="tooltip" data-placement="top" title="Stop"
                class="btn btn-icon btn-outline-primary" disabled>
                {!!\App\Library\Helpers\Helper::getSvgIconeByAction('STOP')!!}
            </button>
        </div>
        <p id="formats"><small>Format: start recording to see sample rate</small></p>
        <input type="file" id="BLOB_FILE" name="audio_data" value="">
        <ul class="list-unstyled" id="recordingsList"></ul>
        <!-- recoder -->
    </div>
</div>
<script src="{{ asset('new-assets/js/recorder.js') }}"></script>
<script src="{{ asset('new-assets/js/recorder-form.js') }}"></script>
<script src="{{ asset('new-assets/js/main.js') }}"></script>