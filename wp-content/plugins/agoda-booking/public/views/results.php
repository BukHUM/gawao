<?php
/**
 * Results display template
 *
 * @package Agoda_Booking
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Ensure $results is set
if ( ! isset( $results ) ) {
	$results = array();
}

if ( empty( $results ) || ! is_array( $results ) ) {
	?>
	<div class="agoda-error">
		<p><?php esc_html_e( 'No hotels found.', 'agoda-booking' ); ?></p>
	</div>
	<?php
	return;
}
?>

<div class="agoda-results">
	<?php foreach ( $results as $hotel ) : ?>
		<div class="agoda-hotel-card">
			<?php if ( ! empty( $hotel['imageURL'] ) ) : ?>
				<div class="agoda-hotel-image-container">
					<img 
						src="<?php echo esc_url( $hotel['imageURL'] ); ?>" 
						alt="<?php echo esc_attr( $hotel['hotelName'] ); ?>" 
						class="agoda-hotel-image" 
						loading="lazy"
						decoding="async"
					/>
				</div>
			<?php endif; ?>
			
			<div class="agoda-hotel-info">
				<h3 class="agoda-hotel-name"><?php echo esc_html( $hotel['hotelName'] ); ?></h3>
				
				<?php if ( ! empty( $hotel['starRating'] ) ) : ?>
					<div class="agoda-hotel-rating" aria-label="<?php echo esc_attr( sprintf( __( '%d star rating', 'agoda-booking' ), $hotel['starRating'] ) ); ?>">
						<div class="agoda-star-rating">
							<?php
							$stars = (int) $hotel['starRating'];
							for ( $i = 1; $i <= 5; $i++ ) {
								if ( $i <= $stars ) {
									echo '<span class="agoda-star">★</span>';
								} else {
									echo '<span class="agoda-star empty">★</span>';
								}
							}
							?>
						</div>
						<span class="agoda-sr-only"><?php echo esc_html( $hotel['starRating'] ); ?> <?php esc_html_e( 'stars', 'agoda-booking' ); ?></span>
					</div>
				<?php endif; ?>
				
				<?php if ( ! empty( $hotel['reviewScore'] ) ) : ?>
					<div class="agoda-hotel-review">
						<span class="agoda-review-score"><?php echo esc_html( $hotel['reviewScore'] ); ?></span>
						<span>/10</span>
						<span class="agoda-sr-only"><?php esc_html_e( 'Review score', 'agoda-booking' ); ?></span>
					</div>
				<?php endif; ?>
				
				<div class="agoda-hotel-price">
					<?php if ( ! empty( $hotel['crossedOutRate'] ) && $hotel['crossedOutRate'] > $hotel['dailyRate'] ) : ?>
						<div class="agoda-price-original">
							<?php echo esc_html( $hotel['currency'] ); ?> <?php echo esc_html( number_format( $hotel['crossedOutRate'], 2 ) ); ?>
						</div>
						<?php if ( ! empty( $hotel['discountPercentage'] ) ) : ?>
							<span class="agoda-price-discount">
								-<?php echo esc_html( $hotel['discountPercentage'] ); ?>%
							</span>
						<?php endif; ?>
					<?php endif; ?>
					<div class="agoda-price-current">
						<?php echo esc_html( $hotel['currency'] ); ?> <?php echo esc_html( number_format( $hotel['dailyRate'], 2 ) ); ?>
						<span class="agoda-price-per-night"><?php esc_html_e( 'per night', 'agoda-booking' ); ?></span>
					</div>
				</div>
				
				<?php if ( ( ! empty( $hotel['freeWifi'] ) && $hotel['freeWifi'] ) || ( ! empty( $hotel['includeBreakfast'] ) && $hotel['includeBreakfast'] ) ) : ?>
					<div class="agoda-hotel-amenities">
						<?php if ( ! empty( $hotel['freeWifi'] ) && $hotel['freeWifi'] ) : ?>
							<span class="agoda-amenity"><?php esc_html_e( 'Free WiFi', 'agoda-booking' ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $hotel['includeBreakfast'] ) && $hotel['includeBreakfast'] ) : ?>
							<span class="agoda-amenity"><?php esc_html_e( 'Breakfast Included', 'agoda-booking' ); ?></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				
				<?php if ( ! empty( $hotel['landingURL'] ) ) : ?>
					<a href="<?php echo esc_url( $hotel['landingURL'] ); ?>" target="_blank" rel="noopener noreferrer" class="agoda-booking-button" aria-label="<?php echo esc_attr( sprintf( __( 'Book %s', 'agoda-booking' ), $hotel['hotelName'] ) ); ?>">
						<?php esc_html_e( 'Book Now', 'agoda-booking' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	<?php endforeach; ?>
</div>
