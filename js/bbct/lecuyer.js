
function uGen(old, a, q, r, m) {
    var t;

    t = Math.floor(old / q);
    t = a * (old - (t * q)) - (t * r);
    return Math.round((t < 0) ? (t + m) : t);
}

function LEnext() {
    var i;

    this.gen1 = uGen(this.gen1, 40014, 53668, 12211, 2147483563);
    this.gen2 = uGen(this.gen2, 40692, 52774, 3791, 2147483399);

    i = Math.floor(this.state / 67108862);

    this.state = Math.round((this.shuffle[i] + this.gen2) % 2147483563);

    this.shuffle[i] = this.gen1;

    return this.state;
}

function LEnint(n) {
    var p = 1;

    while (n >= p) {
	p <<= 1;
    }
    p--;

    while (true) {
    	var v = this.next() & p;

	if (v <= n) {
	    return v;
	}
    }
}

function LEcuyer(s) {
    var i;

    this.shuffle = new Array(32);
    this.gen1 = this.gen2 = (s & 0x7FFFFFFF);
    for (i = 0; i < 19; i++) {
        this.gen1 = uGen(this.gen1, 40014, 53668, 12211, 2147483563);
    }

    for (i = 0; i < 32; i++) {
        this.gen1 = uGen(this.gen1, 40014, 53668, 12211, 2147483563);
        this.shuffle[31 - i] = this.gen1;
    }
    this.state = this.shuffle[0];
    this.next = LEnext;
    this.nextInt = LEnint;
}
