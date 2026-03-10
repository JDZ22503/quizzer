/**
 * Conversion Manager
 * Handles auto-conversion logic based on Subject and Medium
 * Also manages dynamic font-family switching for native look
 */
window.ConversionManager = {
    config: {
        subjects: {
            hindi: 'hindi',
            gujarati: 'gujarati'
        },
        mediums: {
            gujarati: 'gujarati'
        },
        fonts: {
            hindi: "'Inter', sans-serif", // Unicode Hindi usually looks fine with Inter/system
            gujarati: "'Inter', sans-serif", // Default to system, can be customized
            default: "'Inter', sans-serif"
        }
    },

    getConverter: function(type) {
        if (type === 'hindi') return window.HindiConverter;
        if (type === 'gujarati') return window.GujaratiConverter;
        return null;
    },

    determineLanguage: function(subjectName, medium) {
        subjectName = (subjectName || '').toLowerCase().trim();
        medium = (medium || '').toLowerCase().trim();

        if (subjectName === this.config.subjects.hindi) return 'hindi';
        if (subjectName === this.config.subjects.gujarati || medium === this.config.mediums.gujarati) {
            return 'gujarati';
        }
        return null;
    },

    updateFont: function(field, langType) {
        if (!field) return;
        // You can add specific font families here if needed
        // For now, we ensure the direction and unicode support is optimal
        if (langType === 'gujarati' || langType === 'hindi') {
            field.style.fontFamily = this.config.fonts[langType];
        } else {
            field.style.fontFamily = this.config.fonts.default;
        }
    },

    handleField: function(fieldId, subjectName, medium) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        const langType = this.determineLanguage(subjectName, medium);
        const converter = this.getConverter(langType);

        // Dynamic Font Update
        this.updateFont(field, langType);

        if (converter && field.value) {
            const original = field.value;
            const converted = converter.convert(original);
            if (original !== converted) {
                field.value = converted;
            }
        }
    },

    initAutoConversion: function(fieldIds, subjectSelector, mediumSelector) {
        const triggers = () => {
            const subjectEl = document.querySelector(subjectSelector);
            const mediumEl = document.querySelector(mediumSelector);

            let subjectName = '';
            if (subjectEl && subjectEl.selectedIndex !== -1) {
                const opt = subjectEl.options[subjectEl.selectedIndex];
                subjectName = opt.getAttribute('data-name') || opt.textContent;
            }

            const medium = mediumEl ? mediumEl.value : '';

            fieldIds.forEach(id => this.handleField(id, subjectName, medium));
        };

        const subjectEl = document.querySelector(subjectSelector);
        const mediumEl = document.querySelector(mediumSelector);

        if (subjectEl) subjectEl.addEventListener('change', triggers);
        if (mediumEl) mediumEl.addEventListener('change', triggers);

        fieldIds.forEach(id => {
            const field = document.getElementById(id);
            if (!field) return;

            field.addEventListener('input', triggers);
            field.addEventListener('paste', () => setTimeout(triggers, 10));
        });

        // Initial run
        window.addEventListener('load', triggers);
    }
};
