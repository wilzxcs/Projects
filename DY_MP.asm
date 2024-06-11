.text
start:
	li $v0, 4	#introduction message
	la $a0, intro
	syscall
	li $v0, 4
	la $a0, enter_infix
	syscall
	li $v0, 8
	la $a0, infix
	la $a1, 100
 	syscall
 	beq $a1,-2,end
 	beq $a1,-3,start
 	
	li $v0, 4	#prompt INFIX 
	la $a0, prompt_infix
	syscall
	li $v0, 4
	la $a0, infix
	syscall

	li $s7,0	#status: 0 = initially receive nothing, 1 = receive number, 2 = receive operator, 3 = receive '(', 4 = receive ')'
	li $t9,0	
	li $t5,-1	#POSTFIX stack top offset
	li $t6,-1	#OPERATOR stack top offset
	la $t1, infix	#initialize INFIX byte address
	la $t2, postfix	#initialize POSTFIX stack byte addres
	la $t3, operator #initiatlize OPERATOR stack byte address
	addi $t1,$t1,-1	#set initial address of INFIX to -1
	
scan_infix: #input INFIX expression, check if input is valid or not
	addi $t1,$t1,1	#increase INFIX position (becomes 0 from initial)
	lb $t4, ($t1)	#load current char in current position
	beq $t4, ' ', scan_infix	#if current char is ' ', else ignore
	beq $t4, '\n', end_input #if newline operator reached (end of input), pop all operators to POSTFIX stack
	beq $t9,0,digit_1 #if state is 0
	beq $t9,1,digit_2 #if state is 1
	beq $t9,2,digit_3 #if state is 2
	continue_scan:
		beq $t4, '+', plus_min
		beq $t4, '-', plus_min
		beq $t4, '*', mul_div
		beq $t4, '/', mul_div
		beq $t4, '(', open_parenthesis
		beq $t4, ')', closed_bracket
		
invalid_input:	#if input is invalid
	li $v0, 4
 	la $a0, error
 	syscall
 	j end

finish_scan:	#end of scanning the input
	li $v0, 4	#prompt
	la $a0, prompt_postfix
	syscall
	li $t6,-1	#set offset of POSTFIX stack to -1
	
print_postfix_stack:
	addi $t6,$t6,1		#increment POSTFIX offset by 1 (becomes 0 from initial) 
	add $t8,$t2,$t6		#load address of POSTFIX stack
	lbu $t7,($t8)		#load current index of POSTFIX stack
	bgt $t6,$t5,finish_print	#print POSTFIX stack
	bgt $t7,99,print_operator	#if current index of POSTFIX > 99, print operator

	li $v0, 1	# if not, then current index of POSTFIX is a number
	add $a0,$t7,$zero
	syscall
	li $v0, 11
	li $a0, ' '
	syscall
	j print_postfix_stack		#loop
	
	print_operator:
		li $v0, 11
		addi $t7,$t7,-100	#decode operator (ASCII)
		add $a0,$t7,$zero
		syscall
		li $v0, 11
		li $a0, ' '
		syscall
		j print_postfix_stack		#loop
finish_print:
	li $v0, 11
	li $a0, '\n'
	syscall
#calculate
	li $t9,-4	#set top of stack offset to -4
	la $t3,stack	#load stack address
	li $t6,-1	#set POSTFIX offset to -1
	l.s $f0,converter #load converter (converts char to float)
	
calculate_postfix_stack:
	addi $t6,$t6,1	#current POSTFIX offset + 1 (becomes 0 from initial)
	add $t8,$t2,$t6	#load address of POSTFIX
	lbu $t7,($t8)	#load current index of POSTFIX
	bgt $t6,$t5,printResult
	bgt $t7,99,calculate	#if current index of POSTFIX is > 99, it is an operator, then pop top 2 numbers to calculate
	
	addi $t9,$t9,4		#current stack offset
	add $t4,$t3,$t9		#current stack address
	sw $t7,word_to_convert	
	l.s $f10,word_to_convert	#convert number into float
	div.s $f10,$f10,$f0
	s.s $f10,($t4)		#push number into POSTFIX stack
	sub.s $f10,$f10,$f10	#reset f10
	j calculate_postfix_stack		
	
	calculate:
		add $t4,$t3,$t9		#pop 1st number
		l.s $f3,($t4)

		addi $t9,$t9,-4		#pop 2nd number
		add $t4,$t3,$t9		
		l.s $f2,($t4)
		
		beq $t7,143,plus	#ASCII values of operators + 100
		beq $t7,145,minus
		beq $t7,142,multiply
		beq $t7,147,divide
		
		plus:
			add.s $f1,$f2,$f3
			s.s $f1,($t4)
			sub.s $f2,$f2,$f2	#reset f2 and f3
			sub.s $f3,$f3,$f3	
			j calculate_postfix_stack
		minus:
			sub.s $f1,$f2,$f3
			s.s $f1,($t4)	
			sub.s $f2,$f2,$f2	#reset f2 and f3
			sub.s $f3,$f3,$f3
			j calculate_postfix_stack
		multiply:
			mul.s $f1,$f2,$f3
			s.s $f1,($t4)	
			sub.s $f2,$f2,$f2	#reset f2 and f3
			sub.s $f3,$f3,$f3
			j calculate_postfix_stack
		divide:
			div.s $f1,$f2,$f3
			s.s $f1,($t4)	
			sub.s $f2,$f2,$f2	#reset f2 and f3
			sub.s $f3,$f3,$f3
			j calculate_postfix_stack
		
printResult:	# print result
	li $v0, 4
	la $a0, prompt_result
	syscall
	li $v0, 2
	l.s $f12,($t4)
	syscall
	li $v0, 11
	li $a0, '\n'
	syscall
end:
 	li $v0, 10
 	syscall

#subprograms
end_input:
	beq $s7,2,invalid_input
	beq $s7,3,invalid_input			
	beq $t5,-1,invalid_input			
	j pop_all
	
digit_1:	#1st digit
	beq $t4,'0',store_first_digit
	beq $t4,'1',store_first_digit
	beq $t4,'2',store_first_digit
	beq $t4,'3',store_first_digit
	beq $t4,'4',store_first_digit
	beq $t4,'5',store_first_digit
	beq $t4,'6',store_first_digit
	beq $t4,'7',store_first_digit
	beq $t4,'8',store_first_digit
	beq $t4,'9',store_first_digit
	j continue_scan
	
digit_2: 	#second digit
	beq $t4,'0',store_second_digit
	beq $t4,'1',store_second_digit
	beq $t4,'2',store_second_digit
	beq $t4,'3',store_second_digit
	beq $t4,'4',store_second_digit
	beq $t4,'5',store_second_digit
	beq $t4,'6',store_second_digit
	beq $t4,'7',store_second_digit
	beq $t4,'8',store_second_digit
	beq $t4,'9',store_second_digit
	
	jal number_to_post	#if do not receive second digit
	j continue_scan
	
digit_3: #if third digit is received, invalid_input
	beq $t4,'0',invalid_input
	beq $t4,'1',invalid_input
	beq $t4,'2',invalid_input
	beq $t4,'3',invalid_input
	beq $t4,'4',invalid_input
	beq $t4,'5',invalid_input
	beq $t4,'6',invalid_input
	beq $t4,'7',invalid_input
	beq $t4,'8',invalid_input
	beq $t4,'9',invalid_input

	jal number_to_post	#if do not receive third digit
	j continue_scan
	
plus_min: #input + or -
	beq $s7,2,invalid_input	#receive operator after operator or open parenthesis
	beq $s7,3,invalid_input
	beq $s7,0,invalid_input	#receive operator before any number
	li $s7,2		#statue = 1
	continue_plus_minus:
		beq $t6,-1,push_to_operator_stack	#if OPERATOR stack empty, push
		add $t8,$t6,$t3				#load address of top OPERATOR stack
		lb $t7,($t8)				#load top index of OPERATOR stack
		beq $t7,'(',push_to_operator_stack	#if top is (, push
		beq $t7,'+',equal_precedence		#if top + or -
		beq $t7,'-',equal_precedence
		beq $t7,'*',lower_precedence		#if top * or /
		beq $t7,'/',lower_precedence
		
mul_div:	#input * or /
	beq $s7,2,invalid_input	#input consectutive operator or open parenthesis
	beq $s7,3,invalid_input
	beq $s7,0,invalid_input	#input operator before number
	li $s7,2			#change input status to 1
	beq $t6,-1,push_to_operator_stack #if OPERATOR stack empty, push
	add $t8,$t6,$t3			#load address of top OPERATOR stack
	lb $t7,($t8)			#load top index of OPERATOR stack
	beq $t7,'(',push_to_operator_stack		#if top is (, push
	beq $t7,'+',push_to_operator_stack		#if top + or -, push
	beq $t7,'-',push_to_operator_stack
	beq $t7,'*',equal_precedence	#if top * or /
	beq $t7,'/',equal_precedence
	
open_parenthesis:	#input (
	beq $s7,1,invalid_input		#if receive open parenthesis after num or closed bra
	beq $s7,4,invalid_input
	li $s7,3			#status = 1
	j push_to_operator_stack
	
closed_bracket:	#input )
	beq $s7,2,invalid_input		#receive closed parenthesis after operator
	beq $s7,3,invalid_input	
	li $s7,4
	add $t8,$t6,$t3			#load address of top OPERATOR stack
	lb $t7,($t8)			#load top index of OPERATOR stack
	beq $t7,'(',invalid_input	#input (_), error
	continue_closed_parenthesis:
		beq $t6,-1,invalid_input	#no (, error
		add $t8,$t6,$t3				
	lb $t7,($t8)				
		beq $t7,'(',parenthesis_pair	#find parenthesis pair
		jal operator_to_postfix		#push the top of OPERATOR to POSTFIX stack
		j continue_closed_parenthesis	# loop
			
equal_precedence:	#receive +,- and top is +,-,  OR  receive *,/ and top is *,/
	jal operator_to_postfix		#pop top of OPERATOR to POSTFIX stack
	j push_to_operator_stack	#push new operator
	
lower_precedence:	#receive +,- and top is *,/
	jal operator_to_postfix			# Pop the top of Operator to Postfix
	j continue_plus_minus		# Loop again
	
push_to_operator_stack:	 #push input to OPERATOR stack
	add $t6,$t6,1	 #OPERATOR offset + 1 
	add $t8,$t6,$t3	 #Load address of top Operator 
	sb $t4,($t8)			# Store input in Operator
	j scan_infix
	
operator_to_postfix:	#pop the top of OPERATOR and push into POSTFIX
	addi $t5,$t5,1	#POSTFIX offset + 1
	add $t8,$t5,$t2	#address of POSTFIX
	addi $t7,$t7,100#encode operator + 100
	sb $t7,($t8)	#store operator into POSTFIX
	addi $t6,$t6,-1	#OPERATOR offset - 1
	jr $ra
	
parenthesis_pair:	#discard matched parenthesiss
	addi $t6,$t6,-1	#top of OPERATOR offset - 1
	j scan_infix
	
pop_all:#pop OPERATOR to POSTFIX stack 
	jal number_to_post
	beq $t6,-1,finish_scan	#if OPERATOR empty, stop scanning
	add $t8,$t6,$t3		#load address of top OPERATOR 
	lb $t7,($t8)		#load byte value of top OPERATOR
	beq $t7,'(',invalid_input	#if matched parenthesis, invalid_input
	beq $t7,')',invalid_input
	jal operator_to_postfix
	j pop_all
	
store_first_digit:
	beq $s7,4,invalid_input	#receive number after )
	addi $s4,$t4,-48 	#store first digit as number
	add $t9,$zero,1		#change status to 1
	li $s7,1
	j scan_infix
	
store_second_digit:
	beq $s7,4,invalid_input	#receive number after )
	addi $s5,$t4,-48	#store second digit as number
	mul $s4,$s4,10
	add $s4,$s4,$s5		#stored number = first digit * 10 + second digit
	add $t9,$zero,2		#change status to 2
	li $s7,1
	j scan_infix
	
number_to_post:	#store number in POSTFIX stack
	beq $t9,0,end_number_to_post
	addi $t5,$t5,1
	add $t8,$t5,$t2			
	sb $s4,($t8)		#store byte
	add $t9,$zero,$zero	#change status to 0
	end_number_to_post:
	jr $ra
	
.data
	infix: .space 100
	operator: .space 100
	postfix: .space 100
	converter: .word 1
	word_to_convert: .word 1
	stack: .float
	intro: .asciiz "Infix to Postfix calculator. (+,-,/,*) only. 0-99 only.\n"
	enter_infix: .asciiz "Enter an Infix expression: "
	prompt_infix: .asciiz "Infix expression: "
	prompt_postfix: .asciiz "Postfix expression: "
	prompt_result: .asciiz "Result: "
	error: .asciiz "Input error.\n"
