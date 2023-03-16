import ServerSideRender from '@wordpress/server-side-render';
import { useBlockProps } from '@wordpress/block-editor';
import { SelectControl } from '@wordpress/components';
import { PanelBody } from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';

const edit = ( props ) => {
	const blockProps = useBlockProps();
	return (
		<div {...blockProps} >
			<InspectorControls>
				<PanelBody title="Product Categories">
					<SelectControl
						label="Select Category"
						value={ props.attributes.category }
						options={ props.attributes.categories }
						onChange={ ( category ) => {
							props.setAttributes( { category: category } );
						} }
					/>
				</PanelBody>
			</InspectorControls>
        
                <ServerSideRender 
                    block="realsoccer/products"
                    attributes={ 
                        {
                            category: props.attributes.category,
                            className: props.className,
                        }
                        
                    }
                />
		</div>
	);
};

export default edit;