/**
 * Interactivity for the Impact Showcase block.
 *
 * @package GreenGrowth_Impact_Showcase
 */

import { store, getContext, getElement } from '@wordpress/interactivity';

const getShowcaseRoot = ( element ) =>
	element?.closest?.( '[data-wp-interactive="greengrowth-showcase"]' ) || null;

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
		const showExploreButton = root.querySelector( '.gg-explore-button' ) !== null;
		const exploreButtonText = root.querySelector( '.gg-explore-button' )?.textContent || 'Explore More';

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
				const showExploreButton = root.querySelector( '.gg-explore-button' ) !== null;
				const exploreButtonText = root.querySelector( '.gg-explore-button' )?.textContent || 'Explore More';

				context.displayedProjects.forEach( ( project ) => {
					const card = createProjectCard( project, showExploreButton, exploreButtonText );
					grid.appendChild( card );
				} );

				// Show/hide empty state
				const emptyState = root.querySelector( '.gg-empty-state' );
				if ( emptyState ) {
					emptyState.style.display = context.displayedProjects.length === 0 ? '' : 'none';
				}
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
	},
} );
