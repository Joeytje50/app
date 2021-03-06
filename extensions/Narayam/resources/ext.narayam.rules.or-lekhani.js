/**
 * Lekhani regular expression rules table for Oriya
 * @author Junaid P V ([[user:Junaidpv]])
 * @date 2011-11-20
 * @credit With help from Subhashish Panigrahi
 * License: GPLv3
 */
// Normal rules
var rules = [
['\\\\([A-Za-z\\>_~\\.0-9])', '\\\\','$1'],

['([କ-ହୟୱ])a', '','$1ା'],
['([କ-ହୟୱ])i', '','$1\u0b3f'],
['([କ-ହୟୱ])I', '','$1ୀ'],
['([କ-ହୟୱ])u', '','$1\u0b41'],
['([କ-ହୟୱ])(U|\u0b41u)', '','$1\u0b42'],
['([କ-ହୟୱ])R', '','$1\u0b43'],
['([କ-ହୟୱ])\u0b43R', '','$1\u0b44'],
['([କ-ହୟୱ])୍ଳ୍l', '','$1ୢ'],
['([କ-ହୟୱ])ୢl', '','$1ୣ'],
['([କ-ହୟୱ])e', '','$1େ'],
['([କ-ହୟୱ])ାi', '','$1ୈ'],
['([କ-ହୟୱ])o', '','$1ୋ'],
['([କ-ହୟୱ])(ାu|ୋu)', '','$1ୌ'],
['([କ-ହୟୱ])E', '','$1\u0B48'],

['([କ-ହୟୱ])y', '', '$1୍ୟ'], // <consonant>y

['ଅa', '','ଆ'],
['(ଅi|ଏe)', '','ଐ'],
['(ଅu|ଓo|ଓO)', '','ଔ'],
['ଋR', '','ୠ'], // RR
['ଳl', '','ଌ'], // Ll
['ଌl', '','ୡ'], // Lll
['ଞ୍ଚh', '', 'ଞ୍ଛ'],	// nch
['ଞ୍ଜh', '', 'ଞ୍ଝ'], // njh
['ଙ୍କh', '', 'ଙ୍ଖ'], // nkh
['ଙ୍ଗh', '', 'ଙ୍ଘ'],	// ngh
['ମ୍ବh', '', 'ମ୍ଭ'], // mbh or nbh
['ଜ୍ଜh', '', 'ଜ୍ଝ'], // jjh

['ଚ୍ଚh', '', 'ଚ୍ଛ'], // cch

['ଣG', '', 'ଙ'],	// NG
['ଣg', '', 'ଞ'],	// Ng
['କh', '','ଖ'], // kh
['ଗh', '','ଘ'], // gh
['ନc', '', 'ଞ୍ଚ'],	// nc
['ନg', '', 'ଙ୍ଗ'],	// ng
['ଚh', '','ଛ'], // ch
['ଜh', '','ଝ'], // jh
['ନj', '', 'ଞ୍ଜ'],	// nj
['ନk', '', 'ଙ୍କ'],	// nk
['ଟh', '','ଠ'], // Th
['ଡh', '','ଢ'], // Dh
['ତh', '','ଥ'], // th
['ଦh', '','ଧ'], // dh
['(f|ପh)', '','ଫ'], // ph or f
['ବh', '','ଭ'], // bh
['ସh', '','ଷ'], // sh
['(ମb|ନb)', '', 'ମ୍ବ'], // mb or nb
['ଂM', '', 'ଁ'], // MM

['କk', '', 'କ୍କ'], // kk
['ଗg', '', 'ଗ୍ଗ'], // gg
['ଚc', '', 'ଚ୍ଚ'], // cc
['ଜj', '', 'ଜ୍ଜ'], // jj
['ଟT', '', 'ଟ୍ଟ'], // TT
['ଡD', '', 'ଡ୍ଡ'], // DD
['ତt', '', 'ତ୍ତ'], // tt


['ଆ\\\\', '', '\u0B3E'], // aa sign
['ଇ\\\\', '', '\u0B3F'], // i sign
['ଈ\\\\', '', '\u0B40'],// I sign
['ଉ\\\\', '', '\u0B41'], // u sign
['ଉ\\\\', '', '\u0B42'], // U sign
['ଋ\\\\', '', '\u0B43'], // R sign
['ୠ\\\\', '', '\u0B44'], // RR sign
['ଌ\\\\', '', '\u0B62'], // L sign
['ୡ\\\\', '', '\u0B63'], // LL sign
['ଏ\\\\', '', '\u0B47'], // e sign
['ଐ\\\\', '', '\u0B48'], // ai sign
['ଓ\\\\', '', '\u0B4B'], // o sign
['ଔ\\\\', '', '\u0B4C'], // au sign

['\u200c?a', '','ଅ'],
['b', '','ବ'],
['c','','ଚ'],
['d', '','ଦ'],
['\u200c?e', '','ଏ'],
['g', '','ଗ'],
['h', '','ହ'],
['\u200c?i', '','ଇ'],
['j', '','ଜ'],
['k', '','କ'],
['l', '','ଲ'],
['m', '','ମ'],
['n', '','ନ'],
['\u200c?o', '','ଓ'],
['p', '','ପ'],
['q', '', 'ଡ଼'],
['r', '','ର'],
['s', '','ସ'],
['t', '','ତ'],
['\u200c?u', '','ଉ'],
['[vwVW]', '', 'ୱ'],
['x', '','କ୍ଷ'],
['[yY]', '', 'ୟ'],
['z', '','\u0B3C'],
['\u200c?A', '','ଆ'],
['B', '','ବ'],
['C', '','ଛ'],
['D', '','ଡ'],
['\u200c?E', '','ଐ'],
['F', '','ଫ'],
['G', '','ଗ'],
['H', '','ଃ'],
['\u200c?I', '','ଈ'],
['J', '','ଯ'],
['K', '','କ୍କ'],
['L', '','ଳ'],
['M', '','ଂ'],
['N', '','ଣ'],
['\u200c?O', '', 'ଔ'],
['P', '','ପ୍ପ'],
['Q', '', 'ଢ଼'],
['R', '','ଋ'],
['S', '','ଶ'],
['T', '','ଟ'],
['\u200c?U', '','ଊ'],
['X', '', 'ଁ'],
['Z', '', 'ଜ୍ଞ'],
['\\~', '','୍'],
['//', '','ଽ'],
['_', '', '\u200c'],
['0', '','୦'],
['1', '','୧'],
['2', '','୨'],
['3', '','୩'],
['4', '','୪'],
['5', '','୫'],
['6', '','୬'],
['7', '','୭'],
['8', '','୮'],
['9', '','୯']
];

jQuery.narayam.addScheme( 'or-lekhani', {
	'namemsg': 'narayam-or-lekhani',
	'extended_keyboard': false,
	'lookbackLength': 4,
	'keyBufferLength': 2,
	'rules': rules
} );
