'use strict';
/*
navigator.mediaDevices.enumerateDevices().then(function(devices) {
     devices.forEach(function(device) {
  //alert(device.kind);
  if(device.kind=="videoinput"){
     //If device is a video input add to array.
     navigator.getUserMedia({audio: false, video:  { sourceId: VideoId } }, successCallback, errorCallback);
  }
});
});
*/

/*function hasGetUserMedia() {
  return !!(navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
);
}

if (hasGetUserMedia()) {
  // Good to go!
  /*
	MediaStreamTrack.getSources(function(sourceInfos) {
	  var videoSource = null;
	
	  for (var i = 0; i != sourceInfos.length; ++i) {
	    var sourceInfo = sourceInfos[i];
	    
	    if (sourceInfo.kind === 'video') {
	      console.log(sourceInfo.id, sourceInfo.label || 'camera');
	
	      videoSource = sourceInfo.id;
	    } else {
	      console.log('Some other kind of source: ', sourceInfo);
	    }
	  }
	
	  sourceSelected(videoSource);
	});
	*/
	var myConstraints = {
    audio: false, 
    video: {
        facingMode: "environment"
    }
	};

	navigator.getUserMedia(myConstraints).then(function(stream) {
	    video.srcObject = stream;
	    video.play();
	}).catch(errorCallback);
	  


function sourceSelected(videoSource) {
  var constraints = {

    video: {
      optional: [{sourceId: videoSource}]
    }
  };

  navigator.getUserMedia(constraints, successCallback, errorCallback);
} 

function successCallback(stream) {
  window.stream = stream; // make stream available to console
  videoElement.src = window.URL.createObjectURL(stream);
  videoElement.play();
}

function errorCallback(error) {
  console.log('navigator.getUserMedia error: ', error);
}


/*
var videoElement = document.querySelector('video');
var audioSelect = document.querySelector('select#audioSource');
var videoSelect = document.querySelector('select#videoSource');

navigator.getUserMedia = navigator.getUserMedia ||
  navigator.webkitGetUserMedia || navigator.mozGetUserMedia;

function gotSources(sourceInfos) {
  for (var i = 0; i !== sourceInfos.length; ++i) {
    var sourceInfo = sourceInfos[i];
    var option = document.createElement('option');
    option.value = sourceInfo.id;
    if (sourceInfo.kind === 'audio') {
      option.text = sourceInfo.label || 'microphone ' +
        (audioSelect.length + 1);
      audioSelect.appendChild(option);
    } else if (sourceInfo.kind === 'video') {
      option.text = sourceInfo.label || 'camera ' + (videoSelect.length + 1);
      videoSelect.appendChild(option);
    } else {
      console.log('Some other kind of source: ', sourceInfo);
    }
  }
}

if (typeof MediaStreamTrack === 'undefined' ||
    typeof MediaStreamTrack.getSources === 'undefined') {
  console.log('Try Chrome.');
} else {
  MediaStreamTrack.getSources(gotSources);
}

function successCallback(stream) {
  window.stream = stream; // make stream available to console
  videoElement.src = window.URL.createObjectURL(stream);
  videoElement.play();
}

function errorCallback(error) {
  console.log('navigator.getUserMedia error: ', error);
}

function start() {
  if (window.stream) {
    videoElement.src = null;
    window.stream.stop();
  }
  var audioSource = audioSelect.value;
  var videoSource = videoSelect.value;
  var constraints = {
    audio: {
      optional: [{
        sourceId: audioSource
      }]
    },
    video: {
      optional: [{
        sourceId: videoSource
      }]
    }
  };
  navigator.getUserMedia(constraints, successCallback, errorCallback);
}

audioSelect.onchange = start;
videoSelect.onchange = start;

start();
*/