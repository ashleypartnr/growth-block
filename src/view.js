/**
 * Interactivity for the Impact Showcase block.
 *
 * @package GreenGrowth_Impact_Showcase
 */

import { store, getContext, getElement } from '@wordpress/interactivity';

const getShowcaseRoot = ( element ) =>
	element?.closest?.( '[data-wp-interactive="greengrowth-showcase"]' ) || null;

const getExploreConfig = ( root ) => {
	if ( ! root ) {
		return { showExploreButton: false, exploreButtonText: 'Explore More' };
	}

	const showExploreButton = root.dataset.showExploreButton === 'true';
	const exploreButtonText =
		root.dataset.exploreButtonText || 'Explore More';

	return { showExploreButton, exploreButtonText };
};

const isMobileViewport = () => window.innerWidth <= 768;

const scrollGridIntoView = ( root ) => {
	if ( ! root || ! isMobileViewport() ) {
		return;
	}

	const grid = root.querySelector( '.gg-projects-grid' );
	if ( ! grid ) {
		return;
	}

	const filterBar = root.querySelector( '.gg-filter-buttons' );
	const isSticky = filterBar?.classList.contains( 'is-sticky' );
	const offset = filterBar ? filterBar.offsetHeight : 0;
	const gap = 8;
	const firstCard = grid.querySelector( '.gg-project-card' );

	if ( ! isSticky || ! firstCard ) {
		return;
	}

	const cardTop = firstCard.getBoundingClientRect().top + window.scrollY;

	window.scrollTo( {
		top: Math.max( 0, cardTop - offset - gap ),
		behavior: 'smooth',
	} );
};

/**
 * Normalize heights of titles and excerpts within each row
 * so that cards align properly in the grid.
 */
const normalizeCardHeights = ( root ) => {
	if ( ! root ) {
		return;
	}

	const grid = root.querySelector( '.gg-projects-grid' );
	if ( ! grid ) {
		return;
	}

	const cards = Array.from( grid.querySelectorAll( '.gg-project-card' ) );
	const visibleCards = cards.filter( ( card ) => card.style.display !== 'none' );

	if ( visibleCards.length === 0 ) {
		return;
	}

	// Reset min-heights first
	visibleCards.forEach( ( card ) => {
		const title = card.querySelector( '.gg-project-title' );
		const excerpt = card.querySelector( '.gg-project-excerpt' );
		if ( title ) {
			title.style.minHeight = '';
		}
		if ( excerpt ) {
			excerpt.style.minHeight = '';
		}
	} );

	// Group cards by row based on their offsetTop position
	const rows = [];
	let currentRow = [];
	let currentTop = null;

	visibleCards.forEach( ( card ) => {
		const top = card.offsetTop;
		if ( currentTop === null || Math.abs( top - currentTop ) < 10 ) {
			// Same row (within 10px tolerance for sub-pixel rendering)
			currentRow.push( card );
			currentTop = top;
		} else {
			// New row
			if ( currentRow.length > 0 ) {
				rows.push( currentRow );
			}
			currentRow = [ card ];
			currentTop = top;
		}
	} );

	// Add the last row
	if ( currentRow.length > 0 ) {
		rows.push( currentRow );
	}

	// Normalize heights within each row (skip single-column layouts)
	rows.forEach( ( rowCards ) => {
		// Skip normalization if there's only 1 card in the row (single column layout)
		if ( rowCards.length === 1 ) {
			return;
		}

		// Find max title height in this row
		let maxTitleHeight = 0;
		let maxExcerptHeight = 0;

		rowCards.forEach( ( card ) => {
			const title = card.querySelector( '.gg-project-title' );
			const excerpt = card.querySelector( '.gg-project-excerpt' );

			if ( title ) {
				maxTitleHeight = Math.max( maxTitleHeight, title.offsetHeight );
			}
			if ( excerpt ) {
				maxExcerptHeight = Math.max( maxExcerptHeight, excerpt.offsetHeight );
			}
		} );

		// Apply min-heights to all cards in the row
		rowCards.forEach( ( card ) => {
			const title = card.querySelector( '.gg-project-title' );
			const excerpt = card.querySelector( '.gg-project-excerpt' );

			if ( title && maxTitleHeight > 0 ) {
				title.style.minHeight = `${ maxTitleHeight }px`;
			}
			if ( excerpt && maxExcerptHeight > 0 ) {
				excerpt.style.minHeight = `${ maxExcerptHeight }px`;
			}
		} );
	} );
};

const createProjectCard = ( project, showExploreButton, exploreButtonText ) => {
	const article = document.createElement( 'article' );
	article.className = 'gg-project-card';
	article.setAttribute( 'data-wp-key', project.id );
	article.setAttribute( 'data-service-areas', project.serviceAreas.join( ',' ) );

	const link = document.createElement( 'a' );
	link.href = project.link;
	link.className = 'gg-project-card-link';

	const imageDiv = document.createElement( 'div' );
	imageDiv.className = 'gg-project-image';

	if ( project.image && project.image.url ) {
		const img = document.createElement( 'img' );
		img.src = project.image.url;
		img.alt = project.image.alt || '';
		img.loading = 'lazy';
		img.width = 800;
		img.height = 600;
		imageDiv.appendChild( img );
	}

	const contentDiv = document.createElement( 'div' );
	contentDiv.className = 'gg-project-content';

	const title = document.createElement( 'h3' );
	title.className = 'gg-project-title';
	title.textContent = project.title;

	const excerpt = document.createElement( 'p' );
	excerpt.className = 'gg-project-excerpt';
	excerpt.textContent = project.excerpt;

	contentDiv.appendChild( title );
	contentDiv.appendChild( excerpt );

	if ( showExploreButton ) {
		const button = document.createElement( 'span' );
		button.className = 'gg-explore-button';
		button.textContent = exploreButtonText;

		// Add arrow icon
		const svg = document.createElementNS( 'http://www.w3.org/2000/svg', 'svg' );
		svg.setAttribute( 'class', 'gg-explore-icon' );
		svg.setAttribute( 'width', '20' );
		svg.setAttribute( 'height', '20' );
		svg.setAttribute( 'viewBox', '0 0 20 20' );
		svg.setAttribute( 'fill', 'none' );
		svg.setAttribute( 'aria-hidden', 'true' );

		const path = document.createElementNS( 'http://www.w3.org/2000/svg', 'path' );
		path.setAttribute( 'd', 'M4 10H16M16 10L11 5M16 10L11 15' );
		path.setAttribute( 'stroke', 'currentColor' );
		path.setAttribute( 'stroke-width', '2' );
		path.setAttribute( 'stroke-linecap', 'round' );
		path.setAttribute( 'stroke-linejoin', 'round' );

		svg.appendChild( path );
		button.appendChild( svg );
		contentDiv.appendChild( button );
	}

	link.appendChild( imageDiv );
	link.appendChild( contentDiv );
	article.appendChild( link );

	return article;
};

const loadMoreProjects = ( context, root ) => {
	if ( context.isLoading || ! context.hasMore ) {
		return;
	}

	context.isLoading = true;

	// Get the next batch of projects
	const nextProjects = context.filteredProjects.slice(
		context.currentOffset,
		context.currentOffset + context.postsPerPage
	);

	// Add them to displayed projects
	context.displayedProjects = [ ...context.displayedProjects, ...nextProjects ];
	context.currentOffset += nextProjects.length;
	context.hasMore = context.currentOffset < context.filteredProjects.length;

	// Render the new cards
	const grid = root.querySelector( '.gg-projects-grid' );
	if ( grid ) {
		// Get block attributes from existing elements
		const { showExploreButton, exploreButtonText } = getExploreConfig( root );

		nextProjects.forEach( ( project ) => {
			const card = createProjectCard( project, showExploreButton, exploreButtonText );
			// Apply filter visibility
			if ( context.selectedArea !== 'all' && ! project.serviceAreas.includes( context.selectedArea ) ) {
				card.style.display = 'none';
			}
			grid.appendChild( card );
		} );
	}

	// Update loading state
	setTimeout( () => {
		context.isLoading = false;
		// Normalize card heights after new cards are added
		normalizeCardHeights( root );
	}, 300 );
};

store( 'greengrowth-showcase', {
	state: {
		get hasProjects() {
			const context = getContext();
			return context.filteredProjects && context.filteredProjects.length > 0;
		},
		get announcement() {
			const context = getContext();
			const count = context.filteredProjects
				? context.filteredProjects.length
				: 0;
			return `Showing ${ count } project${
				count === 1 ? '' : 's'
			} in ${ context.selectedAreaLabel }`;
		},
		get isActive() {
			const context = getContext();
			return context.buttonArea === context.selectedArea;
		},
	},
	actions: {
		filterByArea( event ) {
			const context = getContext();
			const { ref } = getElement();
			const button = event?.target?.closest( '.gg-filter-button' ) || ref;
			if ( ! button ) {
				return;
			}

			const area = button.getAttribute( 'data-area' ) || 'all';
			const label = button.textContent.trim();

			context.selectedArea = area;
			context.selectedAreaLabel = label;
			context.filteredProjects =
				area === 'all'
					? context.allProjects
					: context.allProjects.filter(
							( project ) =>
								project.serviceAreas &&
								project.serviceAreas.includes( area )
					  );

			// Reset pagination
			context.displayedProjects = context.filteredProjects.slice( 0, context.postsPerPage );
			context.currentOffset = context.postsPerPage;
			context.hasMore = context.currentOffset < context.filteredProjects.length;

			const root = getShowcaseRoot( button );
			if ( ! root ) {
				return;
			}

			// Remove all cards from DOM
			const grid = root.querySelector( '.gg-projects-grid' );
			if ( grid ) {
				const cards = grid.querySelectorAll( '.gg-project-card' );
				cards.forEach( ( card ) => card.remove() );

				// Re-render displayed projects
				const { showExploreButton, exploreButtonText } = getExploreConfig( root );

				context.displayedProjects.forEach( ( project ) => {
					const card = createProjectCard( project, showExploreButton, exploreButtonText );
					grid.appendChild( card );
				} );

				// Show/hide empty state
				const emptyState = root.querySelector( '.gg-empty-state' );
				if ( emptyState ) {
					emptyState.style.display = context.displayedProjects.length === 0 ? '' : 'none';
				}

				// Normalize card heights after filtering
				setTimeout( () => {
					normalizeCardHeights( root );
				}, 50 );

				scrollGridIntoView( root );
			}
		},
	},
	callbacks: {
		initInfiniteScroll() {
			const context = getContext();
			const { ref } = getElement();
			const root = getShowcaseRoot( ref );

			if ( ! root || ! ref ) {
				return;
			}

			// Create Intersection Observer
			const observer = new IntersectionObserver(
				( entries ) => {
					entries.forEach( ( entry ) => {
						if ( entry.isIntersecting && context.hasMore && ! context.isLoading ) {
							loadMoreProjects( context, root );
						}
					} );
				},
				{
					root: null,
					rootMargin: '200px',
					threshold: 0,
				}
			);

			// Observe the sentinel element
			observer.observe( ref );

			// Cleanup on unmount
			return () => {
				observer.disconnect();
			};
		},
		initCardHeightNormalization() {
			const { ref } = getElement();
			const root = getShowcaseRoot( ref );

			if ( ! root ) {
				return;
			}

			// Normalize heights on initial load
			setTimeout( () => {
				normalizeCardHeights( root );
			}, 100 );

			// Recalculate on window resize using requestAnimationFrame for instant response
			let resizeTimeout;
			let rafId;
			const handleResize = () => {
				// Cancel any pending animation frame
				if ( rafId ) {
					cancelAnimationFrame( rafId );
				}

				// Clear existing timeout
				clearTimeout( resizeTimeout );

				// Use RAF for immediate visual update
				rafId = requestAnimationFrame( () => {
					normalizeCardHeights( root );
					rafId = null;
				} );

				// Debounce for final recalculation after resize stops
				resizeTimeout = setTimeout( () => {
					normalizeCardHeights( root );
				}, 50 );
			};

			window.addEventListener( 'resize', handleResize );

			// Cleanup
			return () => {
				window.removeEventListener( 'resize', handleResize );
				if ( rafId ) {
					cancelAnimationFrame( rafId );
				}
				clearTimeout( resizeTimeout );
			};
		},
		initStickyFilterBar() {
			const { ref } = getElement();
			const root = getShowcaseRoot( ref );

			if ( ! root ) {
				return;
			}

			const filterButtons = root.querySelector( '.gg-filter-buttons' );
			if ( ! filterButtons ) {
				return;
			}

			// Only apply on mobile (<=768px)
			const isMobile = () => window.innerWidth <= 768;

			if ( ! isMobile() ) {
				return;
			}

			// Create placeholder to prevent layout shift when sticky
			const placeholder = document.createElement( 'div' );
			placeholder.className = 'gg-filter-placeholder';
			placeholder.style.display = 'none';

			// Track initial position
			const initialTop = filterButtons.getBoundingClientRect().top + window.scrollY;
			let isSticky = false;

			// Handle vertical scroll (sticky behavior)
			const handleScroll = () => {
				if ( ! isMobile() ) {
					return;
				}

				const scrollTop = window.scrollY;
				const shouldBeSticky = scrollTop > initialTop;

				if ( shouldBeSticky && ! isSticky ) {
					// Make sticky
					filterButtons.classList.add( 'is-sticky' );
					placeholder.style.display = 'block';
					placeholder.style.height = `${ filterButtons.offsetHeight }px`;
					filterButtons.parentNode.insertBefore( placeholder, filterButtons );
					isSticky = true;
				} else if ( ! shouldBeSticky && isSticky ) {
					// Remove sticky
					filterButtons.classList.remove( 'is-sticky' );
					placeholder.style.display = 'none';
					if ( placeholder.parentNode ) {
						placeholder.parentNode.removeChild( placeholder );
					}
					isSticky = false;
				}
			};

			// Handle horizontal scroll (fade gradient indicators)
			const handleHorizontalScroll = () => {
				if ( ! isMobile() ) {
					return;
				}

				const scrollLeft = filterButtons.scrollLeft;
				const maxScroll = filterButtons.scrollWidth - filterButtons.clientWidth;

				// At start (hide left gradient)
				if ( scrollLeft <= 5 ) {
					filterButtons.classList.add( 'at-start' );
				} else {
					filterButtons.classList.remove( 'at-start' );
				}

				// At end (hide right gradient)
				if ( scrollLeft >= maxScroll - 5 ) {
					filterButtons.classList.add( 'at-end' );
				} else {
					filterButtons.classList.remove( 'at-end' );
				}
			};

			// Handle resize (cleanup on desktop)
			const handleResize = () => {
				if ( ! isMobile() && isSticky ) {
					filterButtons.classList.remove( 'is-sticky', 'at-start', 'at-end' );
					if ( placeholder.parentNode ) {
						placeholder.parentNode.removeChild( placeholder );
					}
					isSticky = false;
				}
			};

			// Initialize scroll position classes
			handleHorizontalScroll();

			// Attach event listeners
			window.addEventListener( 'scroll', handleScroll, { passive: true } );
			filterButtons.addEventListener( 'scroll', handleHorizontalScroll, { passive: true } );
			window.addEventListener( 'resize', handleResize );

			// Cleanup
			return () => {
				window.removeEventListener( 'scroll', handleScroll );
				filterButtons.removeEventListener( 'scroll', handleHorizontalScroll );
				window.removeEventListener( 'resize', handleResize );
				if ( placeholder.parentNode ) {
					placeholder.parentNode.removeChild( placeholder );
				}
			};
		},
	},
} );
