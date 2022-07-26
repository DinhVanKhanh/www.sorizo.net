
    var maxLineLength = 64;

    var hexSentinel = "?HX?", hexEndSentinel = "?H";

    function armour_hex(b) {
	var h = hexSentinel + byteArrayToHex(b) + hexEndSentinel;
	var t = "";
	while (h.length > maxLineLength) {
	    t += h.substring(0, maxLineLength) + "\n";
	    h = h.substring(maxLineLength, h.length);
	}
	t += h + "\n";
	return t;
    }

    function disarm_hex(s) {
	var hexDigits = "0123456789abcdefABCDEF";
	var hs = "", i;
	
	if ((i = s.indexOf(hexSentinel)) >= 0) {
	    s = s.substring(i + hexSentinel.length, s.length);
	}
	if ((i = s.indexOf(hexEndSentinel)) >= 0) {
	    s = s.substring(0, i);
	}
	
	for (i = 0; i < s.length; i++) {
	    var c = s.charAt(i);
	    if (hexDigits.indexOf(c) >= 0) {
	    	hs += c;
	    }
	}
//dump("hs", hs);
	return hexToByteArray(hs);
    }

    var acgcl, acgt, acgg;

    function armour_cg_outgroup() {
	if (acgcl.length > maxLineLength) {
	    acgt += acgcl + "\n";
	    acgcl = "";
	}
	if (acgcl.length > 0) {
	    acgcl += " ";
	}
	acgcl += acgg;
	acgg = "";
    }

    function armour_cg_outletter(l) {
	if (acgg.length >= 5) {
	    armour_cg_outgroup();
	}
	acgg += l;
    }

    var codegroupSentinel = "ZZZZZ";

    function armour_codegroup(b) {
    	var charBase = ("A").charCodeAt(0);
	
	acgcl = codegroupSentinel;
	acgt = "";
	acgg = "";
	
	var cgrng = new LEcuyer(0xbadf00d);
	for (i = 0; i < b.length; i++) {
	   var r = cgrng.nextInt(23);
	   armour_cg_outletter(String.fromCharCode(charBase + ((((b[i] >> 4) & 0xF)) + r) % 24));
	   r = cgrng.nextInt(23);
	   armour_cg_outletter(String.fromCharCode(charBase + ((((b[i] & 0xF)) + r) % 24)));
	}
	delete cgrng;

	while (acgg.length < 5) {
	    armour_cg_outletter("Z");
	}
	armour_cg_outgroup();

	acgg = "YYYYY";
	armour_cg_outgroup();

	acgt += acgcl + "\n";
	
	return acgt;
    }
    
    var dcgs, dcgi;

    function disarm_cg_insig() {
    	while (dcgi < dcgs.length) {
	    var c = dcgs.charAt(dcgi++).toUpperCase();
	    if ((c >= "A") && (c <= "Z")) {
	    	return c;
    	    }
    	}
	return "";
    }

    function disarm_codegroup(s) {
    	var b = new Array();
	var nz = 0, ba, bal = 0, c;
	
    	dcgs = s;
	dcgi = 0;

	while (nz < 5) {
	    c = disarm_cg_insig();
	    
	    if (c == "Z") {
	    	nz++;
	    } else if (c == "") {
	    	nz = 0;
	    	break;
	    } else {
	    	nz = 0;
	    }
    	}
	
	if (nz == 0) {
	    alert("No codegroup starting symbol found in message.");
	    return "";
	}
	
	/*  Decode letter pairs from successive groups
	    and assemble into bytes.  */
	
	var charBase = ("A").charCodeAt(0);    
	var cgrng = new LEcuyer(0xbadf00d);
	for (nz = 0; nz < 2; ) {
	    c = disarm_cg_insig();
	    
	    if ((c == "Y") || (c == "")) {
	    	break;
	    } else if (c != "Z") {
	    	var r = cgrng.nextInt(23);
	    	var n = c.charCodeAt(0) - charBase;
		n = (n + (24 - r)) % 24;
		if (nz == 0) {
		    ba = (n << 4);
		    nz++;
		} else {
		    ba |= n;
		    b[bal++] = ba;
		    nz = 0;
		}
	    }
	}
	delete cgrng;

	var kbo = "  Attempting decoding with data received.";
	if (nz != 0) {
	    alert("Codegroup data truncated." + kbo);
	} else {
	    if (c == "Y") {
		nz = 1;
		while (nz < 5) {
		    c = disarm_cg_insig();
	    	    if (c != "Y") {
			break;
		    }
		    nz++;
		}
		if (nz != 5) {
		    alert("Codegroup end group incomplete." + kbo);
		}
	    } else {
		alert("Codegroup end group missing." + kbo);
	    }
	}
	
	return b;
    }

    var base64code = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
    	base64sent = "?b64", base64esent = "?64b", base64addsent = true;

    function armour_base64(b) {
	var b64t = "";
	var b64l = base64addsent ? base64sent : "";
	
	var i;
	for (i = 0; i <= b.length - 3; i += 3) {
	    if ((b64l.length + 4) > maxLineLength) {
	    	b64t += b64l + "\n";
	    	b64l = "";
	    }
	    b64l += base64code.charAt(b[i] >> 2);
	    b64l += base64code.charAt(((b[i] & 3) << 4) | (b[i + 1] >> 4));
	    b64l += base64code.charAt(((b[i + 1] & 0xF) << 2) | (b[i + 2] >> 6));
	    b64l += base64code.charAt(b[i + 2] & 0x3F);
	}

	if ((b.length - i) == 1) {
	    b64l += base64code.charAt(b[i] >> 2);
	    b64l += base64code.charAt(((b[i] & 3) << 4));
	    b64l += "==";
	} else if ((b.length - i) == 2) {
	    b64l += base64code.charAt(b[i] >> 2);
	    b64l += base64code.charAt(((b[i] & 3) << 4) | (b[i + 1] >> 4));
	    b64l += base64code.charAt(((b[i + 1] & 0xF) << 2));
	    b64l += "=";
	}

	if ((b64l.length + 4) > maxLineLength) {
	    b64t += b64l + "\n";
	    b64l = "";
	}
	if (base64addsent) {
	    b64l += base64esent;
	}
	b64t += b64l + "\n";
	return b64t;
    }

    function disarm_base64(s) {
    	var b = new Array();
	var i = 0, j, c, shortgroup = 0, n = 0;
	var d = new Array();
	
	if ((j = s.indexOf(base64sent)) >= 0) {
	    s = s.substring(j + base64sent.length, s.length);
	}
	if ((j = s.indexOf(base64esent)) >= 0) {
	    s = s.substring(0, j);
	}

	while (i < s.length) {
	    if (base64code.indexOf(s.charAt(i)) != -1) {
	    	break;
	    }
	    i++;
	}

	while (i < s.length) {
	    for (j = 0; j < 4; ) {
	    	if (i >= s.length) {
		    if (j > 0) {
		    	alert("Base64 cipher text truncated.");
		    	return b;
		    }
		    break;
		}
		c = base64code.indexOf(s.charAt(i));
		if (c >= 0) {
		    d[j++] = c;
		} else if (s.charAt(i) == "=") {
		    d[j++] = 0;
		    shortgroup++;
		} else if (s.substring(i, i + base64esent.length) == base64esent) {
		    i = s.length;
		    continue;
		} else {
		}
		i++;
	    }
	    if (j == 4) {
	    	b[n++] = ((d[0] << 2) | (d[1] >> 4)) & 0xFF;
		if (shortgroup < 2) {
		    b[n++] = ((d[1] << 4) | (d[2] >> 2)) & 0xFF;
		    if (shortgroup < 1) {
		    	b[n++] = ((d[2] << 6) | d[3]) & 0xFF;
		    }
		}
	    }
    	}
	return b;
    }
