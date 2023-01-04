/**
 * Multiple Choice Block
 * 
 * npm install react-color
 */

import "../../scss/multiple-choice.scss"
import "../../scss/admin/multiple-choice.scss"
import {TextControl, Flex, FlexBlock, FlexItem, Button, Icon, PanelBody, PanelRow, ColorPicker} from "@wordpress/components"
import {InspectorControls, BlockControls, AlignmentToolbar} from "@wordpress/block-editor"
import {ChromePicker} from "react-color"

(function() {
    let locked = false

    wp.data.subscribe(function() {
        const results = wp.data.select("core/block-editor").getBlocks().filter(function(block) {
            return block.name == "locs-blocks/multiple-choice" && block.attributes.correctAnswer == undefined
        })

        if (results.length && locked == false) {
            locked = true
            wp.data.dispatch("core/editor").lockPostSaving("noanswer")
        }

        if (!results.length && locked) {
            locked = false
            wp.data.dispatch("core/editor").unlockPostSaving("noanswer")
        }
    })
})() // Immediately Invoked Function Expression (IIFE)

 wp.blocks.registerBlockType("locs-blocks/multiple-choice", {
    title: "Multiple Choice",
    description: "Add a simple multiple choice box to your pages and posts to increase engagement.",
    icon: "smiley",
    category: "locs_blocks", 
    attributes: {
        question: {type: "string"},
        answers: {type: "array", default: [""]},
        correctAnswer: {type: "number", default: undefined},
        bgColor: {type: "string", default: "#EBEBEB"},
        theAlignment: {type: "string", default: "left"}
    },
    description: "Create multiple choice questions to keep users engaged with your content.",
    example: {
        attributes: {
            question: "What is my name?",
            answers: ['Matthew', 'Mark', 'Luke', 'John'],
            correctAnswer: 3,
            bgColor: "#CFE8F1",
            theAlignment: "center"
        }
    },
    edit: EditComponent,
    save: function (props) {
        return null
    }
})

function EditComponent (props) {

    function updateQuestion(value) {
        props.setAttributes({question: value})
    }

    function deleteAnswer(indexToDelete) {
        const newAnswers = props.attributes.answers.filter(function(x, index) {
            return index != indexToDelete
        })
        props.setAttributes({answers: newAnswers})

        if (indexToDelete == props.attributes.correctAnswer) {
            props.setAttributes({correctAnswer: undefined})
        }
    }

    function markAsCorrect(index) {
        props.setAttributes({correctAnswer: index})
    }

    return (
        <div className="multiple-choice-edit-block" style={{backgroundColor: props.attributes.bgColor}}>
            <BlockControls>
                <AlignmentToolbar value={props.attributes.theAlignment} onChange={x => props.setAttributes({theAlignment: x})}/>
            </BlockControls>
            <InspectorControls>
                <PanelBody title="Background Colour" initialOpen={true}>
                    <PanelRow>
                        <ChromePicker color={props.attributes.bgColor} onChangeComplete={x => props.setAttributes({bgColor: x.hex})} disableAlpha={true} />
                    </PanelRow>
                </PanelBody>
            </InspectorControls>
            <TextControl label="Question:" value={props.attributes.question} onChange={updateQuestion} style={{fontSize: "20px"}} />
            <p style={{fontSize: "13px", margin: "20px 0 8px 0"}}>Answers:</p>
            {props.attributes.answers.map(function (answer, index) {
                return (
                    <Flex>
                        <FlexBlock>
                            <TextControl autoFocus={answer == undefined} value={answer} onChange={newValue => {
                                const newAnswers = props.attributes.answers.concat([])
                                newAnswers[index] = newValue
                                props.setAttributes({answers: newAnswers})
                            }} />
                        </FlexBlock>
                        <FlexItem>
                            <Button onClick={() => markAsCorrect(index)}>
                                <Icon className="mark-as-correct" icon={props.attributes.correctAnswer == index ? "star-filled" : "star-empty" } />
                            </Button>
                        </FlexItem>
                        <FlexItem>
                            <Button isLink className="attention-delete" onClick={() => deleteAnswer(index)}>Delete</Button>
                        </FlexItem>
                    </Flex>
                )
            })}
            <Button isPrimary onClick={() => {
                props.setAttributes({answers: props.attributes.answers.concat([undefined])})
            }}>Add another answer</Button>
        </div>
    )
} 