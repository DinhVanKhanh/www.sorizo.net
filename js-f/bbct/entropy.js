
    var entropyData = new Array();
    var edlen = 0;
 
    addEntropyTime();
    ce();

    function addEntropyByte(b) {
    	entropyData[edlen++] = b;
    }

    function ce() {
    	addEntropyByte(Math.floor((((new Date).getMilliseconds()) * 255) / 999));
    }

    function addEntropy32(w) {
    	var i;
	
	for (i = 0; i < 4; i++) {
	    addEntropyByte(w & 0xFF);
	    w >>= 8;
    	}
    }

    function addEntropyTime() {
    	addEntropy32((new Date()).getTime());
    }

    var mouseMotionCollect = 0;
    var oldMoveHandler;
	
    function mouseMotionEntropy(maxsamp) {
	if (mouseMotionCollect <= 0) {
	    mouseMotionCollect = maxsamp;
	    if ((document.implementation.hasFeature("Events", "2.0")) &&
	    	document.addEventListener) {
	    	//  Browser supports Document Object Model (DOM) 2 events
		document.addEventListener("mousemove", mouseMoveEntropy, false);
	    } else {
		if (document.attachEvent) {
	    	    //  Internet Explorer 5 and above event model
		    document.attachEvent("onmousemove", mouseMoveEntropy);
		} else {
		    //	Internet Explorer 4 event model
	    	    oldMoveHandler = document.onmousemove;
		    document.onmousemove = mouseMoveEntropy;
		}
	    }
	}
    }

    var mouseEntropyTime = 0;
	
    function mouseMoveEntropy(e) {
    	if (!e) {
	    e = window.event;
	}
	if (mouseMotionCollect > 0) {
	    if (mouseEntropyTime-- <= 0) {
	    	addEntropyByte(e.screenX & 0xFF);
	    	addEntropyByte(e.screenY & 0xFF);
	    	ce();
	    	mouseMotionCollect--;
	    	mouseEntropyTime = (entropyData[edlen - 3] ^ entropyData[edlen - 2] ^
		    	    	    entropyData[edlen - 1]) % 19;
    	    }
	    if (mouseMotionCollect <= 0) {
	    	if (document.removeEventListener) {
		    document.removeEventListener("mousemove", mouseMoveEntropy, false);
		} else if (document.detachEvent) {
		    document.detachEvent("onmousemove", mouseMoveEntropy);
		} else {
		    document.onmousemove = oldMoveHandler;
		}
	    }
	}
    }

    function keyFromEntropy() {
	var i, k = new Array(32);
	
	if (edlen == 0) {
	    alert("Blooie!  Entropy vector void at call to keyFromEntropy.");
	}

	md5_init();
	for (i = 0; i < edlen; i += 2) {
	    md5_update(entropyData[i]);
	}
	md5_finish();
    	for (i = 0; i < 16; i++) {
	    k[i] = digestBits[i];
	}

	md5_init();
	for (i = 1; i < edlen; i += 2) {
	    md5_update(entropyData[i]);
	}
	md5_finish();
	for (i = 0; i < 16; i++) {
	    k[i + 16] = digestBits[i];
	}

	return k;
    }
