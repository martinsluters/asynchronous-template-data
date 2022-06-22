'use strict';
import axios from 'axios';
import { addFilter } from '@wordpress/hooks';
import fetchData from '../src/fetchData.js';

jest.mock('axios');

describe('fetchData', () => {
	describe('when WP Ajax call is successful', () => {
		it('should return data from response body', async () => {
			// Given
			const dataReturn = 'Blazing fast delivery';
			axios.post.mockResolvedValueOnce({
				data: dataReturn, // Because axios stores payload in 'data' object property.
			});

			// When
			const result = await fetchData(
				'{"provider_key":"provider-key-x","wp_post_id":2}',
				'http://test.com',
				'action_name_x',
				'security_request_param_x',
				'6dedae452f',
				'Default Failure Message'
			);

			// Then
			expect(axios.post).toHaveBeenCalledWith(
				'http://test.com',
				{
					provider_key: 'provider-key-x',
					wp_post_id: 2,
				},
				{
					params: {
						action: 'action_name_x',
						security_request_param_x: '6dedae452f',
					},
				}
			);
			/* eslint no-console: ["error", { allow: ["log"] }] */
			expect(result).toEqual(dataReturn);
		});
		it('should be able to to filter/amend data from response body', async () => {
			// Given
			const originalDataReturn = 'Original delivery message';
			const amendedDataReturn = 'Amended delivery message';
			axios.post.mockResolvedValueOnce({
				data: originalDataReturn, // Because axios stores payload in 'data' object property.
			});
			addFilter(
				'ml_asynchronous_template_data_section_information',
				'asynchronous_template_data',
				// eslint-disable-next-line no-unused-vars
				(message, productId, providerKey) => {
					return amendedDataReturn;
				}
			);

			// When
			const result = await fetchData(
				'{"provider_key":"provider-key-x","wp_post_id":2}',
				'http://test.com',
				'action_name_x',
				'security_request_param_x',
				'6dedae452f',
				'Default Failure Message'
			);

			// Then
			expect(axios.post).toHaveBeenCalledWith(
				'http://test.com',
				{
					provider_key: 'provider-key-x',
					wp_post_id: 2,
				},
				{
					params: {
						action: 'action_name_x',
						security_request_param_x: '6dedae452f',
					},
				}
			);
			expect(result).toEqual(amendedDataReturn);
		});
	});

	describe('when WP Ajax call fails', () => {
		it('should return default request failure message', async () => {
			// Hide console error while testing
			// const consoleErrroMock = jest
			// 	.spyOn(global.console, 'error')
			// 	.mockImplementation();

			// Given
			const message = 'Network Error';
			axios.post.mockRejectedValueOnce(new Error(message));

			// When
			const result = await fetchData(
				'{"provider_key":"provider-key-x","wp_post_id":2}',
				'http://test.com',
				'action_name_x',
				'security_request_param_x',
				'6dedae452f',
				'Default Failure Message'
			);

			// Then
			expect(axios.post).toHaveBeenCalledWith(
				'http://test.com',
				{
					provider_key: 'provider-key-x',
					wp_post_id: 2,
				},
				{
					params: {
						action: 'action_name_x',
						security_request_param_x: '6dedae452f',
					},
				}
			);
			expect(result).toEqual('Default Failure Message');

			//consoleErrroMock.mockRestore();
		});
		it('should be able to to filter/amend default request failure message', async () => {
			// Given
			const amendedFailureMessage = 'Default Original Failure Message';
			const message = 'Network Error';
			axios.post.mockRejectedValueOnce(new Error(message));
			addFilter(
				'ml_asynchronous_template_data_section_failure_information',
				'asynchronous_template_data',
				// eslint-disable-next-line no-unused-vars
				(failureMessage, productId, providerKey, error) => {
					return amendedFailureMessage;
				}
			);

			// When
			const result = await fetchData(
				'{"provider_key":"provider-key-x","wp_post_id":2}',
				'http://test.com',
				'action_name_x',
				'security_request_param_x',
				'6dedae452f',
				'Default Original Failure Message'
			);

			// Then
			expect(axios.post).toHaveBeenCalledWith(
				'http://test.com',
				{
					provider_key: 'provider-key-x',
					wp_post_id: 2,
				},
				{
					params: {
						action: 'action_name_x',
						security_request_param_x: '6dedae452f',
					},
				}
			);
			expect(result).toEqual(amendedFailureMessage);
		});
	});
});
