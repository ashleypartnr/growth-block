#!/usr/bin/env node

/**
 * Build Verification Script
 * Ensures all required files exist after build
 */

const fs = require('fs');
const path = require('path');

const BUILD_DIR = path.join(__dirname, '..', 'build');

const REQUIRED_FILES = [
	'index.js',
	'style-index.css',
	'block.json',
	'render.php',
	'view.js'
];

console.log('🔍 Verifying build output...\n');

let allFilesExist = true;
let missingFiles = [];

REQUIRED_FILES.forEach(file => {
	const filePath = path.join(BUILD_DIR, file);
	const exists = fs.existsSync(filePath);
	
	if (exists) {
		const stats = fs.statSync(filePath);
		const sizeKB = (stats.size / 1024).toFixed(2);
		console.log(`✅ ${file} (${sizeKB} KB)`);
	} else {
		console.log(`❌ ${file} - MISSING`);
		allFilesExist = false;
		missingFiles.push(file);
	}
});

console.log('\n' + '─'.repeat(50));

if (allFilesExist) {
	console.log('✅ Build verification passed!\n');
	process.exit(0);
} else {
	console.error(`❌ Build verification failed!`);
	console.error(`Missing files: ${missingFiles.join(', ')}\n`);
	process.exit(1);
}
