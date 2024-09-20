/* eslint-disable */
const calcSize = (baseViewport, baseValue, k) => {
    const relativeValueToViewport = (baseValue / baseViewport) * k;
    const baseSize = baseValue - baseViewport * relativeValueToViewport;
    return `calc(${baseSize}px + ${relativeValueToViewport * 100}vw)`;
};
function camelToKebab(str) {
    if (str != str.toLowerCase()) {
        str = str.replace(/[A-Z]/g, (m) => '-' + m.toLowerCase());
    }
    return str;
}
const designs = {
    portrait: 375,
    landscape: 1440,
};
const { portrait, landscape } = designs;

class Observable {
    constructor() {
        this.subscribers = [];
        this.scaleFactors = {
            portrait: [
                { f: 1, min: 0, max: 600 },
                { f: 0.7, min: 600, max: 5000 },
            ],
            landscape: [
                { f: 1, min: 0, max: 1200 },
                { f: 1, min: 1200, max: 1440 },
                { f: 0.5, min: 1440, max: 3000 },
                { f: 1, min: 3000, max: 99999 },
            ],
        };
    }

    subscribe(fn) {
        this.subscribers.push(fn);
    }

    setData(cb) {
        this.data = cb(this.data);
        this.subscribers.forEach((subscriber) => {
            subscriber();
        });
    }
}
const store = new Observable();

const mediaOptions = {
    min(value) {
        return `and ( min-width: ${value}px )`;
    },
    max(value) {
        return `and ( max-width: ${value}px )`;
    },
    orientation(orientation) {
        return `and ( orientation: ${orientation} )`;
    },
};
export class Style2 {
    constructor(identifier) {
        this.identifier = identifier;
        this.styles = [];
    }
    addMediaQuery(options = {}, rules) {
        this.styles.push({
            type: 'query',
            options,
            rules,
        });
        return this;
    }
    formatRuleValue(value, options) {
        switch (typeof value) {
            case 'number':
                return calcSize(designs[options.orientation], value, options.f);
            case 'string':
                const formated = value.replace(
                    /(\d+(\.\d+)?(px)?)/g,
                    (match) => {
                        const strToNum = parseFloat(match);
                        return calcSize(
                            designs[options.orientation],
                            strToNum,
                            options.f
                        );
                    }
                );
                return formated;
            case 'object':
                const { values, callback } = value;
                const generatedValues = values.map((value) =>
                    calcSize(designs[options.orientation], value, options.f)
                );
                return callback(...generatedValues);
        }

        return value;
    }
    formatRules(rules, options) {
        const formated = Object.entries(rules).reduce((acc, [prop, value]) => {
            acc += `\n\t\t${camelToKebab(prop)}: ${this.formatRuleValue(
                value,
                options
            )};`;
            return acc;
        }, '');
        return formated;
    }
    formatStyles(styles, options) {
        const formated = Object.entries(styles).reduce(
            (acc, [selector, rules], i, arr) => {
                acc += `\n\t${selector} {`;
                acc += this.formatRules(rules, options);
                acc += '\n\t}';
                return acc;
            },
            ''
        );
        return formated;
    }
    formatWithMediaOptions(options, styles) {
        const optionsString = Object.entries(options).reduce(
            (acc, [key, value]) => {
                acc += mediaOptions[key] ? mediaOptions[key](value) : '';
                return acc;
            },
            ''
        );
        const result =
            `\n@media screen ${optionsString} {` +
            this.formatStyles(styles, options) +
            '\n}';
        return result;
    }
    update() {
        const el = document.querySelector(`[data-style=${this.identifier}]`);
        if (el) {
            el.remove();
        }
        const style = document.createElement('style');
        style.setAttribute('data-style', this.identifier);

        const content = this.styles.reduce(
            (acc, { type, options, rules }, i, arr) => {
                acc += store.scaleFactors[options.orientation].reduce(
                    (acc, factor) => {
                        if (type === 'query') {
                            acc += this.formatWithMediaOptions(
                                { ...options, ...factor },
                                rules
                            );
                        }
                        return acc;
                    },
                    ''
                );
                return acc;
            },
            ''
        );
        style.innerHTML = content;
        document.getElementsByTagName('head')[0].appendChild(style);
        return this;
    }
}
