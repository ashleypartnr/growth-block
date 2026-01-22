/**
 * Interactivity for the Impact Showcase block.
 *
 * @package GreenGrowth_Impact_Showcase
 */

import { store, getContext, getElement } from '@wordpress/interactivity';

const getShowcaseRoot = ( element ) =>
	element?.closest?.( '[data-wp-interactive="greengrowth-showcase"]' ) || null;

const updateVisibleCards = ( root, area, projects ) => {
	const cards = root.querySelectorAll( '.gg-project-card' );

	cards.forEach( ( card ) => {
		const cardId = card.getAttribute( 'data-wp-key' );
		const project = projects.find(
			( item ) => String( item.id ) === String( cardId )
		);
		const matches =
			area === 'all' ||
			( project?.serviceAreas && project.serviceAreas.includes( area ) );

		card.style.display = matches ? '' : 'none';
	} );

	// Update empty state
	const visibleCards = root.querySelectorAll( '.gg-project-card:not([style*="display: none"])' );
	const emptyState = root.querySelector( '.gg-empty-state[data-wp-class--hidden]' );
	if ( emptyState ) {
		emptyState.style.display = visibleCards.length === 0 ? '' : 'none';
	}
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

			const root = getShowcaseRoot( button );
			if ( ! root ) {
				return;
			}

			updateVisibleCards( root, area, context.allProjects );
		},
	},
} );
