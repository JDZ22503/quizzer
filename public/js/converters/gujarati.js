/**
 * Gujarati Legacy Font to Unicode Converter
 * Matches common Gujarati legacy fonts (like Saral, Gopika, etc.)
 */
window.GujaratiConverter = {
    convert: function(input) {
        if (!input) return "";

        var text = input;

        // If already Unicode Gujarati, don't convert
        if (/[\u0A80-\u0AFF]/.test(text)) {
            return text;
        }

        // Common Gujarati Legacy → Unicode dictionary
        var dict = [
            ['vks', 'ઓ'], ['vkS', 'ઔ'], ['vk', 'આ'], ['v', 'અ'],
            ['bZ', 'ઈ'], ['b', 'ઇ'], ['m', 'ઉ'], [',', 'એ'], [',s', 'ઐ'],
            ['pkS', 'ચૈ'], ['ks', 'ો'], ['kS', 'ૌ'], ['k', 'ા'], ['h', 'ી'],
            ['q', 'ુ'], ['w', 'ૂ'], ['s', 'ે'], ['S', 'ૈ'],
            ['d', 'ક'], ['[', 'ખ'], ['x', 'ગ'], ['?', 'ઘ'],
            ['p', 'ચ'], ['N', 'છ'], ['t', 'જ'], ['>', 'ઝ'],
            ['V', 'ટ'], ['B', 'ઠ'], ['M', 'ડ'], ['<', 'ઢ'], ['.', 'ણ'],
            ['r', 'ત'], ['F', 'થ'], ['n', 'દ'], ['/', 'ધ'], ['u', 'ન'],
            ['i', 'પ'], ['Q', 'ફ'], ['c', 'બ'], ['H', 'ભ'], ['e', 'મ'],
            [';', 'ય'], ['j', 'ર'], ['y', 'લ'], ['G', 'ળ'], ['o', 'વ'],
            ["'", "શ"], ['"', 'ષ'], ['l', 'સ'], ['g', 'હ'],
            ['z', '્ર'], ['~', '્'], ['+', '઼'], ['A', '।'],
            ['-', '.'], ['@', '/'], [']', ',']
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

        // Fix "િ" matra placement (Gujarati uses similar logic to Hindi)
        text = text.replace(/f(.)/g, "$1િ");
        text = text.replace(/fa(.)/g, "$1િં");
        text = text.replace(/(.)Z/g, "ર્$1"); // Although Gujarati reph is different, legacy fonts often use similar tricks

        return text.trim();
    }
};
