/**
 * Application functions for interop between components in different packages.
 *
 * @module application
 */
import gdn from "@core/gdn";

/**
 * Get a piece of metadata passed from the server.
 *
 * @param {string} key - The key to lookup.
 * @param {*=} defaultValue - A fallback value in case the key cannot be found.
 *
 * @returns {*} Returns a meta value or the default value.
 */
export function getMeta(key, defaultValue = undefined) {
    if (!gdn.meta) {
        return defaultValue;
    }

    const parts = key.split('.');
    let haystack = gdn.meta;

    for (const part of parts) {
        haystack = haystack[part];
        if (haystack === undefined) {
            return defaultValue;
        }
    }
    return haystack;
}

/**
 * Set a piece of metadata. This will override what was passed from the server.
 *
 * @param {string} key - The key to store under.
 * @param {*} value - The value to set.
 */
export function setMeta(key, value) {
    if (gdn.meta === null || typeof gdn.meta !== 'object') {
        gdn.meta = {};
    }

    const parts = key.split('.');
    const last = parts.pop();
    let haystack = gdn.meta;

    for (const part of parts) {
        if (haystack[part] === null || typeof haystack[part] !== 'object') {
            haystack[part] = {};
        }
        haystack = haystack[part];
    }
    haystack[last] = value;
}

/**
 * Translate a string into the current locale.
 *
 * @param {string} str The string to translate.
 * @param {string=} defaultTranslation The default translation to use.
 * @returns {string} Returns the translation or the default.
 */
export function translate(str, defaultTranslation) {
    // Codes that begin with @ are considered literals.
    if (str.substr(0, 1) === '@') {
        return str.substr(1);
    }

    if (gdn.translations[str] !== undefined) {
        return gdn.translations[str];
    }

    return defaultTranslation !== undefined ? defaultTranslation : str;
}

/**
 * The t function is an alias for translate.
 *
 * @type {translate}
 */
export const t = translate;

/**
 * Format a URL in the format passed from the controller.
 *
 * @param {string} path - The path to format.
 *
 * @returns {string} Returns a URL that can be used in the APP.
 */
export function formatUrl(path) {
    if (path.indexOf("//") >= 0) {
        return path;
    } // this is an absolute path.

    const urlFormat = getMeta("UrlFormat", "/{Path}");

    if (path.substr(0, 1) === "/") {
        path = path.substr(1);
    }

    if (urlFormat.indexOf("?") >= 0) {
        path = path.replace("?", "&");
    }

    return urlFormat.replace("{Path}", path);
}

/**
 * @type {Object} The currently registered components.
 * @private
 */
const allComponents = {};

/**
 * Register a component in the components registry.
 *
 * @param {string} name The name of the component.
 * @param {React.Component} component The component to register.
 */
export function addComponent(name, component) {
    allComponents[name.toLowerCase()] = component;
}

/**
 * Test to see if a component has been registered.
 *
 * @param {string} name The name of the component to test.
 * @returns {boolean} Returns **true** if the component has been registered or **false** otherwise.
 */
export function componentExists(name) {
    return allComponents[name.toLowerCase()] !== undefined;
}

/**
 * Get a component from the component registry.
 *
 * @param {string} name The name of the component.
 * @returns {React.Component|undefined} Returns the component or **undefined** if there is no registered component.
 */
export function getComponent(name) {
    return allComponents[name.toLowerCase()];
}

/**
 * @type {Array} The currently registered routes.
 * @private
 */
const allRoutes = [];

/**
 * Register one or more routes to the app component.
 *
 * @param {Array} routes An array of routes to add.
 */
export function addRoutes(routes) {
    if (!Array.isArray(routes)) {
        allRoutes.push(routes);
    } else {
        allRoutes.push(...routes);
    }
}

/**
 * Get all of the currently registered routes.
 *
 * @returns {Array} Returns an array of routes.
 */
export function getRoutes() {
    return allRoutes;
}