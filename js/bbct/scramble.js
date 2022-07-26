/*
Copyright(C) 2007-2008 Sorimachi Co.,Ltd. All rights reserved.
 ********************************************************
 * Module Name : scramble.js
 * Function    : support functions for encryption in page
 * System Name : 
 * Create      : Y.Narumi   2008/06/27
 * Update      :
 * Comment     :
 *********************************************************/


    var loadTime = (new Date()).getTime();  // Save time page was loaded
    var key;                                // Key (byte array)
    var prng;                               // Pseudorandom number generator
    

/********************************************************
 * Function    : 鍵作成
 * Param       : mode 0:テキスト, 1:16進
               : 
 * Comment     : 
 ********************************************************/
 
    function Generate_key( mode ) {

        var i, j, k = "";
        
        addEntropyTime();
        var seed = keyFromEntropy();
        
        var prng = new AESprng(seed);
    
        if (mode == 0) {
            // Text key
            var charA = ("A").charCodeAt(0);
            
            for (i = 0; i < 12; i++) {
                if (i > 0) {
                        k += "-";
                }
                for (j = 0; j < 5; j++) {
                    k += String.fromCharCode(charA + prng.nextInt(25));
                }
            }
        } else {
            // Hexadecimal key
            var hexDigits = "0123456789ABCDEF";
            
            for (i = 0; i < 64; i++) {
                k += hexDigits.charAt(prng.nextInt(15));
            }
        }    

        delete prng;

        return  k;

    }


/********************************************************
 * Function    :暗号化
 * Param       : modeNum        
 * Param       : plain          文字列
 * Param       : mode           mode 0:テキスト, 1:16進
 * Param       : encoding       encoding0:codeNumber  1:16進 2:Base64
 * Param       : return      normalcy ,  text文字列
 * Comment     : 
 ********************************************************/
 
    function get_Encrypt_text(modeNum, plain, mode, encoding) {
        var v, i;
        
        var returnObject = new Object();
        returnObject["normalcy"] = false;
        returnObject["text"] = "";
        
         //Undefiend modeNumber
        if (modeNum.length == 0) {
            return  returnObject;
        }
        
        //Check null 
        if (plain  == null) {
            return  returnObject;
        }

        if (plain.length == 0) {
            returnObject["normalcy"] = true;
            return  returnObject;
        }
        
        if (setKey(modeNum,  mode) == false) {
            return  returnObject;
        }
        
        addEntropyTime();
        
        prng = new AESprng(keyFromEntropy());

        var plaintext = encode_utf8(plain);
        
        //  Compute MD5 sum of message text and add to header
        
        md5_init();
        for (i = 0; i < plaintext.length; i++) {
            md5_update(plaintext.charCodeAt(i));
        }
        md5_finish();

        var header = "";
        
        for (i = 0; i < digestBits.length; i++) {
            header += String.fromCharCode(digestBits[i]);
        }
        
        //  Add message length in bytes to header
        i = plaintext.length;
        
        header += String.fromCharCode(i >>> 24);
        header += String.fromCharCode(i >>> 16);
        header += String.fromCharCode(i >>> 8);
        header += String.fromCharCode(i & 0xFF);

        /*  The format of the actual message passed to rijndaelEncrypt
        is:
        
           Bytes       Content
           0-15       MD5 signature of plaintext
           16-19       Length of plaintext, big-endian order
          20-end      Plaintext
            
        Note that this message will be padded with zero bytes
        to an integral number of AES blocks (blockSizeInBits / 8).
        This does not include the initial vector for CBC
        encryption, which is added internally by rijndaelEncrypt.
        
       */
       
        var ct = rijndaelEncrypt(header + plaintext, key, "CBC");

        if (encoding ==0) {
            v = armour_codegroup(ct);
        } else if (encoding == 1 ) {
            v = armour_hex(ct);
        } else if (encoding == 2) {
            v = armour_base64(ct);
        } else {
            return  returnObject;
        }
        
        delete prng;
        
        returnObject["normalcy"] = true;
        returnObject["text"] = v;
        
        return  returnObject;
    }

/********************************************************
 * Function    : キーをクラス関数にセットする
 * Param       : modeNum    モードナンバー
 * Param       : mode 0:テキスト, 1:16進
 * Comment     : 
 ********************************************************/
    function setKey(modeNum, mode) {

        if ( mode == 0) { 
            
            var s = encode_utf8(modeNum);

            var i, kmd5e, kmd5o;
    
            if (s.length == 1) {
                s += s;
            }
            
            md5_init();
            for (i = 0; i < s.length; i += 2) {
                md5_update(s.charCodeAt(i));
            }
            md5_finish();
            kmd5e = byteArrayToHex(digestBits);
            
            md5_init();
            for (i = 1; i < s.length; i += 2) {
                md5_update(s.charCodeAt(i));
            }
            md5_finish();
            kmd5o = byteArrayToHex(digestBits);
    
            var hs = kmd5e + kmd5o;
            key =  hexToByteArray(hs);
            hs = byteArrayToHex(key);

        } else {            // Hexadecimal key
        
            var s =modeNum;
            var hexDigits = "0123456789abcdefABCDEF";
            var hs = "", i, bogus = false;
    
            for (i = 0; i < s.length; i++) {
                var c = s.charAt(i);
                if (hexDigits.indexOf(c) >= 0) {
                    hs += c;
                } else {
                    bogus = true;
                }
            }

            if (bogus) {
                return false;
            }

            if (hs.length > (keySizeInBits / 4)) {
                return false;
            } else {
                //  If key is fewer than 64 hex digits, fill it with zeroes
                while (hs.length < (keySizeInBits / 4)) {
                    hs += "0";
                }
            }
            
            key =  hexToByteArray(hs);
        }
        
        return true;
    }
    
    
/********************************************************
 * Function    : エンコードを返す
 * Param       : thisForm    入力チェック対象フォーム
 * Param       : plain       文字列
 * Param       : return      成功 OR 失敗
 * Comment     : 
 ********************************************************/
    /*  Examine the message and determine which kind of ASCII
        armour it uses from the sentinel preceding the message.
    We test for each of the sentinels and, if any are
    found, decide based on the one found first in the
    message (since, for example, the sentinel for
    codegroup armour might appear in a Base64 message,
    but only after the Base64 sentinel).  If none of
    the sentinels are found, we decode using the armour
    type specified by the checkboxes for encryption.
    The return value is an integer which identifies the
    armour type as follows:
    
        0   Codegroup
        1   Hexadecimal
        2   Base 64
    */
    
    function determineArmourType(s, encoding) {
        var kt, pcg, phex, pb64, pmin;
        
        pcg  = s.indexOf(codegroupSentinel);
        phex = s.indexOf(hexSentinel);
        pb64 = s.indexOf(base64sent);

        if (pcg == -1) {
            pcg = s.length;
        }
        if (phex == -1) {
            phex = s.length;
        }
        if (pb64 == -1) {
            pb64 = s.length;
        }
        pmin = Math.min(pcg, Math.min(phex, pb64));
        
        if (pmin < s.length) {
            if (pmin == pcg) {
                kt = 0;
            } else if (pmin == phex) {
                kt = 1;
            } else {
                kt = 2;
            }
        } else {
            kt = encoding;
        }
        
        return kt;
    }

/********************************************************
 * Function    : 複合化
 * Param       : modeNum        modeNum
 * Param       : plain          文字列
 * Param       : mode           mode      0:テキスト, 1:16進
 * Param       : encoding       encoding  0:codeNumber  1:16進 2:Base64
 * return       : return      成功 OR 失敗
 * Comment     : 
 ********************************************************/
 //    Decrypt ciphertext with key, place result in plaintext field
     function Decrypt_text(modeNum , scText, mode, encoding) {
        
        
        var returnObject = new Object();
        returnObject["normalcy"] = false;
        returnObject["text"] = "";
        
        if (modeNum.length == 0) {
            return  returnObject;
        }

        if (scText.length == 0) {
           returnObject["normalcy"] = true;
           return  returnObject;
        }

        if (setKey(modeNum, mode) == false) {
           return  returnObject;
        }

        var ct = new Array(), kt;
        
        kt = determineArmourType(scText, encoding);

        if (kt == 0) {
            ct = disarm_codegroup(scText);
        } else if (kt == 1) {
            ct = disarm_hex(scText);
        } else if (kt == 2) {
            ct = disarm_base64(scText);
        }
    
        var result = rijndaelDecrypt(ct, key, "CBC");
        
        var header = result.slice(0, 20);
        result = result.slice(20);
        
        /*  Extract the length of the plaintext transmitted and
            verify its consistency with the length decoded.  Note
            that in many cases the decrypted messages will include
            pad bytes added to expand the plaintext to an integral
            number of AES blocks (blockSizeInBits / 8).  */
        
        var dl = (header[16] << 24) | (header[17] << 16) | (header[18] << 8) | header[19];

        if ((dl < 0) || (dl > result.length)) {
            return  returnObject;
        }
        
        /*  Compute MD5 signature of message body and verify
            against signature in message.  While we're at it,
            we assemble the plaintext result string.  Note that
            the length is that just extracted above from the
            message, *not* the full decrypted message text.
            AES requires all messages to be an integral number
            of blocks, and the message may have been padded with
            zero bytes to fill out the last block; using the
            length from the message header elides them from
            both the MD5 computation and plaintext result.  */
            
        var i, plaintext = "";
        
        md5_init();
        for (i = 0; i < dl; i++) {
            plaintext += String.fromCharCode(result[i]);
            md5_update(result[i]);
        }
        md5_finish();

        for (i = 0; i < digestBits.length; i++) {
            if (digestBits[i] != header[i]) {
                return  returnObject;
            break;
            }
        }
        
        //  That's it; plug plaintext into the result field
        returnObject["normalcy"] = true;
        returnObject["text"] = decode_utf8(plaintext);
        
        return  returnObject;
    }
    
    
