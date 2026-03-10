/**
 * Hindi Kruti Dev to Unicode Converter
 */
window.HindiConverter = {
    convert: function(input) {
        if (!input) return "";

        var text = input;

        // If already Unicode Hindi, don't convert
        if (/[\u0900-\u097F]/.test(text)) {
            return text;
        }

        // KrutiDev → Unicode dictionary
        var dict = [
            ['Q+Z', 'QZ+'],
            ['sas', 'sa'],
            ['aa', 'a'],
            [')Z', 'र्द्ध'],
            ['ZZ', 'Z'],
            ['=kk', '=k'],
            ['f=k', 'f='],

            ['vks', 'ओ'],
            ['vkS', 'औ'],
            ['vk', 'आ'],
            ['v', 'अ'],
            ['bZ', 'ई'],
            ['b', 'इ'],
            ['m', 'उ'],
            [',', 'ए'],
            [',s', 'ऐ'],

            ['pkS', 'चै'],
            ['ks', 'ो'],
            ['kS', 'ौ'],
            ['k', 'ा'],
            ['h', 'ी'],
            ['q', 'ु'],
            ['w', 'ू'],
            ['s', 'े'],
            ['S', 'ै'],

            ['d', 'क'],
            ['[', 'ख'],
            ['x', 'ग'],
            ['?', 'घ'],
            ['³', 'ङ'],
            ['p', 'च'],
            ['N', 'छ'],
            ['t', 'ज'],
            ['>', 'झ'],
            ['¥', 'ञ'],

            ['V', 'ट'],
            ['B', 'ठ'],
            ['M', 'ड'],
            ['<', 'ढ'],
            ['.', 'ण'],
            ['r', 'त'],
            ['F', 'थ'],
            ['n', 'द'],
            ['/', 'ध'],
            ['u', 'न'],

            ['i', 'प'],
            ['Q', 'फ'],
            ['c', 'ब'],
            ['H', 'भ'],
            ['e', 'म'],

            [';', 'य'],
            ['j', 'र'],
            ['y', 'ल'],
            ['G', 'ळ'],
            ['o', 'व'],

            ["'", "श"],
            ['"', 'ष'],
            ['l', 'स'],
            ['g', 'ह'],

            ['z', '्र'],
            ['~', '्'],
            ['+', '़'],

            ['A', '।'],
            ['-', '.'],
            ['@', '/'],
            [']', ',']
        ];

        // Sort dictionary by longest key first
        dict.sort(function(a, b) {
            return b[0].length - a[0].length;
        });

        // Apply dictionary replacements
        for (var i = 0; i < dict.length; i++) {
            var regex = new RegExp(dict[i][0].replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g');
            text = text.replace(regex, dict[i][1]);
        }

        // Fix "ि" matra placement
        text = text.replace(/f(.)/g, "$1ि");
        text = text.replace(/fa(.)/g, "$1िं");
        text = text.replace(/(.)Z/g, "र्$1");
        text = text.replace(/््/g, '्');

        return text.trim();
    }
};
