
    function AESprng(seed) {
	this.key = new Array();
	this.key = seed;
	this.itext = hexToByteArray("9F489613248148F9C27945C6AE62EECA3E3367BB14064E4E6DC67A9F28AB3BD1");
	this.nbytes = 0;

	this.next = AESprng_next;
	this.nextbits = AESprng_nextbits;
	this.nextInt = AESprng_nextInt;
	this.round = AESprng_round;

	bsb = blockSizeInBits;
	blockSizeInBits = 256;
	var i, ct;
	for (i = 0; i < 3; i++) {
	    this.key = rijndaelEncrypt(this.itext, this.key, "ECB");
	}
	
	var n = 1 + (this.key[3] & 2) + (this.key[9] & 1);    
	for (i = 0; i < n; i++) {
	    this.key = rijndaelEncrypt(this.itext, this.key, "ECB");
	}
	blockSizeInBits = bsb;
    }

    function AESprng_round() {
	bsb = blockSizeInBits;
	blockSizeInBits = 256;
	this.key = rijndaelEncrypt(this.itext, this.key, "ECB");
	this.nbytes = 32;
    	blockSizeInBits = bsb;
    }
    
    //	Return next byte from the generator

    function AESprng_next() {
    	if (this.nbytes <= 0) {
	    this.round();
	}
	return(this.key[--this.nbytes]);
    }


    function AESprng_nextbits(n) {
    	var i, w = 0, nbytes = Math.floor((n + 7) / 8);

	for (i = 0; i < nbytes; i++) {
	    w = (w << 8) | this.next();
	}
	return w & ((1 << n) - 1);
    }

    function AESprng_nextInt(n) {
	var p = 1, nb = 0;

	while (n >= p) {
	    p <<= 1;
	    nb++;
	}
	p--;

	while (true) {
	    var v = this.nextbits(nb) & p;
	    
	    if (v <= n) {
	    	return v;
	    }
	}
    }
