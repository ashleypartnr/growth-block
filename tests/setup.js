/**
 * Jest test setup file.
 *
 * @package GreenGrowth_Impact_Showcase
 */

// Mock WordPress Interactivity API
global.wp = {
	i18n: {
		__: (text) => text,
		_x: (text) => text,
		sprintf: (format, ...args) => format,
	},
	interactivity: {
		store: jest.fn(() => ({})),
		getContext: jest.fn(() => ({})),
		getElement: jest.fn(() => ({})),
	}
};

// Mock IntersectionObserver
global.IntersectionObserver = class IntersectionObserver {
	constructor(callback, options) {
		this.callback = callback;
		this.options = options;
	}

	observe() {
		return null;
	}

	unobserve() {
		return null;
	}

	disconnect() {
		return null;
	}
};

// Mock window.matchMedia
Object.defineProperty(window, 'matchMedia', {
	writable: true,
	value: jest.fn().mockImplementation(query => ({
		matches: false,
		media: query,
		onchange: null,
		addListener: jest.fn(),
		removeListener: jest.fn(),
		addEventListener: jest.fn(),
		removeEventListener: jest.fn(),
		dispatchEvent: jest.fn(),
	})),
});
