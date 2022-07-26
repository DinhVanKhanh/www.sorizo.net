
    function unicode_to_utf8(s) {
	var utf8 = "";

	for (var n = 0; n < s.length; n++) {
            var c = s.charCodeAt(n);

            if (c <= 0x7F) {
        	utf8 += String.fromCharCode(c);
            } else if ((c >= 0x80) && (c <= 0x7FF)) {
        	utf8 += String.fromCharCode((c >> 6) | 0xC0);
        	utf8 += String.fromCharCode((c & 0x3F) | 0x80);
            } else {
        	utf8 += String.fromCharCode((c >> 12) | 0xE0);
        	utf8 += String.fromCharCode(((c >> 6) & 0x3F) | 0x80);
        	utf8 += String.fromCharCode((c & 0x3F) | 0x80);
            }
	}
	return utf8;
    }

    function utf8_to_unicode(utf8) {
	var s = "", i = 0, b1, b2, b2;

	while (i < utf8.length) {
            b1 = utf8.charCodeAt(i);
            if (b1 < 0x80) {
        	s += String.fromCharCode(b1);
        	i++;
            } else if((b1 >= 0xC0) && (b1 < 0xE0)) {
        	b2 = utf8.charCodeAt(i + 1);
        	s += String.fromCharCode(((b1 & 0x1F) << 6) | (b2 & 0x3F));
        	i += 2;
            } else {
        	b2 = utf8.charCodeAt(i + 1);
		b3 = utf8.charCodeAt(i + 2);
        	s += String.fromCharCode(((b1 & 0xF) << 12) |
		    	    	    	 ((b2 & 0x3F) << 6) |
					 (b3 & 0x3F));
        	i += 3;
            }
	}
	return s;
    }

    function encode_utf8(s) {
    	var i, necessary = false;
	
	for (i = 0; i < s.length; i++) {
	    if ((s.charCodeAt(i) == 0x9D) ||
	    	(s.charCodeAt(i) > 0xFF)) {
	    	necessary = true;
		break;
	    }
	}
	if (!necessary) {
	    return s;
	}
	return String.fromCharCode(0x9D) + unicode_to_utf8(s);
    }

    function decode_utf8(s) {
    	if ((s.length > 0) && (s.charCodeAt(0) == 0x9D)) {
	    return utf8_to_unicode(s.substring(1));
	}
	return s;
    }
