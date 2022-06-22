'use strict';
import axios from 'axios';
import { applyFilters } from '@wordpress/hooks';
export default async function fetchData(
	argumentJsonString,
	url,
	action,
	nonceRequestParameterName,
	nonce,
	defaultFailureMessage
) {
	const argument = JSON.parse(argumentJsonString);
	const dataReturn = await axios
		.post(url, argument, {
			params: {
				action,
				[nonceRequestParameterName]: nonce,
			},
		})
		.then((response) => {
			return applyFilters(
				'ml_asynchronous_template_data_section_information',
				response.data,
				argument
			);
		})
		.catch((error) => {
			return applyFilters(
				'ml_asynchronous_template_data_section_failure_information',
				defaultFailureMessage,
				argument,
				error
			);
		});

	return dataReturn;
}
